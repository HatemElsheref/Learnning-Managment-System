<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\University;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;


class UniversityController extends Controller
{
    CONST UNIVERSITY_PATH='universities_avatars';

    use NotificationTrait;
    use RemoveTraite;
    public function __construct() {
        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:read_universities')->only(['index','show']);
        $this->middleware('DashboardPermission:create_universities')->only(['create','store']);
        $this->middleware('DashboardPermission:update_universities')->only(['edit','update','seo']);
        $this->middleware('DashboardPermission:delete_universities')->only(['destroy','MultiDelete']);
    }
    public function index()
    {
        $universities=University::with('departments')->get();
        return view('dashboard.university.index',compact('universities'));
    }

    public function create()
    {
        return view('dashboard.university.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:191|unique:universities,name',
            'address'=>'required|string|max:191',
            'slug'=>'required|string|max:191|unique:universities,slug',
            'description'=>'required|string',
            'photo'=>'required|image:png,jpg,jpeg',
        ]);

        $validated_data=$request->all();
        $request->file('photo')->storeAs(self::UNIVERSITY_PATH,$request->file('photo')->hashName());
        $validated_data['photo']=$request->file('photo')->hashName();
        $university=University::create($validated_data);
        Cache::forget('universities');
        Cache::forget('departments');
        removeCache('department');
        removeCache('university');
        removeCache('course');
        removeCache('feedback');
        if ($university){
//            $this->Notify("University Added Successfully","Success Operation","success",false) ;
            toast('University Added Successfully','success',AlertPosition());
            return redirect()->route('university.index');
        }else{
//            $this->Notify("Failed To Add University","Failed Operation","fail",false) ;
            toast('Failed To Add University','error',AlertPosition());
            return redirect()->route('university.create');
        }

    }

    public function show($id)
    {
        $university=University::with('departments','departments.courses')->find($id);
        if ($university){
            $deps=$university->departments->pluck('id')->toArray();
            $users=User::whereIn('department_id',$deps)->count();
            return view('dashboard.university.show',compact('university'))->with('users',$users);
        }
        else{
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return redirect()->route('university.index');
        }
    }

    public function edit($id)
    {
        $university=University::find($id);
        if ($university){
            return view('dashboard.university.edit',compact('university'));
        } else{
//            $this->Notify("Please, Determine Correct Id","University Not Found","fail",false);
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect(route('university.index'));
        }

    }

    public function update(Request $request, $id)
    {
        $university=University::find($id);
        if (!$university){
//            $this->Notify("Please, Determine Correct Id","University Not Found","fail",false);
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect(route('university.index'));
        }
        $request->validate([
            'name'=>'required|string|max:191|unique:universities,name,'.$id,
            'address'=>'required|string|max:191',
            'slug'=>'required|string|max:191|unique:universities,slug,'.$id,
            'description'=>'required|string',
            'photo'=>'image:png,jpg,jpeg',
        ]);
        $validated_data=$request->all();
        if ($request->hasFile('photo')){
            Storage::disk('local')->delete(self::UNIVERSITY_PATH.DIRECTORY_SEPARATOR.$university->photo);
            $request->file('photo')->storeAs(self::UNIVERSITY_PATH,$request->file('photo')->hashName());
            $validated_data['photo']=$request->file('photo')->hashName();
        } else{
            $validated_data['photo']=$university->photo;
        }

        if ($university->update($validated_data)){
            Cache::forget('universities');
            Cache::forget('departments');
            removeCache('department');
            removeCache('university');
            removeCache('course');
            removeCache('feedback');
//            $this->Notify("University Updated Successfully","Success Operation","success",false) ;
            toast('University Updated Successfully','success',AlertPosition());
            return redirect()->route('university.index');
        }
//        $this->Notify("Failed To Update University","Failed Operation","fail",false) ;
        toast('Failed To Update University','error',AlertPosition());
        return redirect()->route('university.index');
    }

    public function destroy($id)
    {
        if(!DeleteMode()){
            toast('Not Allowed','info',AlertPosition());
            return redirect()-back();
         }


        //remove university will remove departments and courses  inside it
        $university=University::find($id);
        if ($university){
            Storage::disk('local')->delete(self::UNIVERSITY_PATH.DIRECTORY_SEPARATOR.$university->photo);
            self::RemoveUniversity($university);

            $university->delete();
            Cache::forget('universities');
            Cache::forget('departments');
            removeCache('department');
            removeCache('university');
            removeCache('course');
            removeCache('feedback');
//            $this->Notify("University Deleted Successfully","Success Operation","success",false);
            toast('University Deleted Successfully','success',AlertPosition());
            return redirect()->route('university.index');
        }else{
//            $this->Notify("Failed To Delete University","Failed Operation","fail",false) ;
            toast('Failed To Delete University','error',AlertPosition());
//            return redirect()->route('university.index');
            return redirect()->back();
        }

    }

    public function MultiDelete(Request $request)
    {
        //remove university will remove departments and courses  inside it
        $request->validate([
            'universities_id'=>'required|array|min:1'
        ]) ;

            foreach ($request->universities_id as $id){
                $university=University::find($id);
                if ($university){
                    Storage::disk('local')->delete(self::UNIVERSITY_PATH.DIRECTORY_SEPARATOR.$university->photo);
                    self::RemoveUniversity($university);
                    $university->delete();
                    Cache::forget('universities');
                    Cache::forget('departments');

                } else{
                    continue;
                }
            }
        removeCache('department');
        removeCache('university');
        removeCache('course');
        removeCache('feedback');
//            $this->Notify("Universities Deleted Successfully","Success Operation","success",false);
                toast('Selected Universities Deleted Successfully','success',AlertPosition());
//            return redirect()->route('university.index');
            return redirect()->back();
    }

    public function seo(Request $request,$id){

        $university=University::find($id);
        if (!$university){
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect()->back();
        }
        $request->validate([
            'slug'=>'required|string|max:191|unique:universities,slug,'.$id,
            'meta_description'=>'required|string',
            'meta_title'=>'required|string|max:191',
            'meta_keywords'=>'required|array|min:1',
        ]);

        $university->meta_title=$request->meta_title;
        $university->slug=$request->slug;
        $university->meta_description=$request->meta_description;
        $university->meta_keywords=json_encode($request->meta_keywords);
        $university->save();
        Cache::forget('universities');
        Cache::forget('departments');
        removeCache('department');
        removeCache('university');
        removeCache('course');
        removeCache('feedback');
        toast('University Seo Updated Successfully','success',AlertPosition());
        return redirect()->route('university.show',$university->id);

    }
}
