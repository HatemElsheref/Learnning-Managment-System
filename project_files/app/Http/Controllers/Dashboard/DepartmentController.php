<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\University;
use App\Department;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    CONST _PATH='departments_avatars';

    use NotificationTrait;
    use RemoveTraite;

    public function __construct() {

        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:read_departments')->only(['index','show']);
        $this->middleware('DashboardPermission:create_departments')->only(['create','store']);
        $this->middleware('DashboardPermission:update_departments')->only(['edit','update','seo']);
        $this->middleware('DashboardPermission:delete_departments')->only(['destroy','MultiDelete']);
    }

    public function index()
    {
        $departments=Department::with(['university','courses'])->orderBy('created_at','desc')->get();
        return view('dashboard.university.department.index',compact('departments'));
    }

    public function create()
    {
        $universities=University::all();
        if (count($universities)>0){
            return view('dashboard.university.department.create',compact('universities'));
        }   else{
            toast('You Must Add University Before Add Departments ','info',AlertPosition());
            return redirect()->route('university.create');
        }

    }

    public function store(Request $request)
    {

        $request->validate([
            'name'=>['required','string','max:191', Rule::unique('departments','name')->where('university_id',$request->university_id)],
            'slug'=>'required|string|max:191|unique:departments,slug',
            'photo'=>'required|image:png,jpg,jpeg',
            'university_id'=>'required|numeric',
        ]);

        if (!University::find($request->university_id)){
            toast('Failed To Determine University','error',AlertPosition());
            return redirect()->route('department.create');
        }
        $validated_data=$request->all();
        $request->file('photo')->storeAs(self::_PATH,$request->file('photo')->hashName());
        $validated_data['photo']=$request->file('photo')->hashName();
        $department=Department::create($validated_data);
        if ($department){
            Cache::forget('universities');
            Cache::forget('departments');
            removeCache('department');
            removeCache('university');
            removeCache('course');
            removeCache('feedback');
            toast('Department Added Successfully','success',AlertPosition());
            return redirect()->route('department.index');
        }else{
            toast('Failed To Add Department','error',AlertPosition());
            return redirect()->route('department.create');
        }

    }

    public function show($id)
    {
        $department=Department::with(['courses','courses.department','university','courses.instructor'])->find($id);
        if ($department){
            $users=User::where('department_id','=',$department->id)->count();
            return view('dashboard.university.department.show',compact('department'))->with('users',$users);
        }
        else{
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return redirect()->route('department.index');
        }
    }

    public function edit($id)
    {

        $department=Department::find($id);
        if ($department){
            $universities=University::all();
            return view('dashboard.university.department.edit',['department'=>$department,'universities'=>$universities]);
        } else{
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect(route('department.index'));
        }

    }

    public function update(Request $request, $id)
    {
        $department=Department::find($id);
        if (!$department){
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect(route('department.index'));
        }
        $request->validate([
            'name'=>['required','string','max:191', Rule::unique('departments','name')->where('university_id',$request->university_id)->ignore($id)],
            'slug'=>'required|string|max:191|unique:departments,slug,'.$id,
            'university_id',
            'photo'=>'image:png,jpg,jpeg',
        ]);

        if (!University::find($request->university_id)){
            toast('Failed To Determine University','error',AlertPosition());
            return redirect()->route('department.create');
        }

        $validated_data=$request->all();
        if ($request->hasFile('photo')){
            Storage::disk('local')->delete(self::_PATH.DIRECTORY_SEPARATOR.$department->photo);
            $request->file('photo')->storeAs(self::_PATH,$request->file('photo')->hashName());
            $validated_data['photo']=$request->file('photo')->hashName();
        } else{
            $validated_data['photo']=$department->photo;
        }

        $department=$department->update($validated_data);
        if ($department){
            Cache::forget('universities');
            Cache::forget('departments');
            removeCache('department');
            removeCache('university');
            removeCache('course');
            removeCache('feedback');
            toast('Department Updated Successfully','success',AlertPosition());
            return redirect()->route('department.index');
        }
        toast('Failed To Update Department','error',AlertPosition());
        return redirect()->route('department.index');
    }

    public function destroy($id)
    {
        //remove department will remove  courses  inside it
        $department=Department::find($id);
        if ($department){
            Storage::disk('local')->delete(self::_PATH.DIRECTORY_SEPARATOR.$department->photo);
            self::RemoveDepartment($department);
            $department->delete();
            Cache::forget('universities');
            Cache::forget('departments');
            removeCache('department');
            removeCache('university');
            removeCache('course');
            removeCache('feedback');
            toast('Department Deleted Successfully','success',AlertPosition());
            return redirect()->route('department.index');
        }else{
            toast('Failed To Delete Department','error',AlertPosition());
            return redirect()->route('university.index');
        }
    }

    public function MultiDelete(Request $request)
    {
        //remove department will remove courses  inside it
        $request->validate([
            'departments_id'=>'required|array|min:1'
        ]) ;

        foreach ($request->departments_id as $id){
            $department=Department::find($id);
            if ($department){
                Storage::disk('local')->delete(self::_PATH.DIRECTORY_SEPARATOR.$department->photo);
                self::RemoveDepartment($department);
                $department->delete();
            } else{
                continue;
            }
        }
        Cache::forget('universities');
        Cache::forget('departments');
        removeCache('department');
        removeCache('university');
        removeCache('course');
        removeCache('feedback');
        toast('Selected Departments Deleted Successfully','success',AlertPosition());
        return redirect()->route('department.index');
    }

    public function seo(Request $request,$id){

        $department=Department::find($id);
        if (!$department){
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect()->back();
        }
        $request->validate([
            'slug'=>'required|string|max:191|unique:departments,slug,'.$id,
            'meta_description'=>'required|string',
            'meta_title'=>'required|string|max:191',
            'meta_keywords'=>'required|array|min:1',
        ]);

        $department->meta_title=$request->meta_title;
        $department->slug=$request->slug;
        $department->meta_description=$request->meta_description;
        $department->meta_keywords=json_encode($request->meta_keywords);
        $department->save();
        Cache::forget('universities');
        Cache::forget('departments');
        removeCache('department');
        toast('Department Seo Updated Successfully','success',AlertPosition());
        return redirect()->route('department.show',$department->id);

    }
}
