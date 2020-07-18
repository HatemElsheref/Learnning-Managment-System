<?php

namespace App\Http\Controllers\Dashboard;

use App\course;
use App\Http\Controllers\Controller;
use App\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    CONST _PATH='instructors_avatars';

    use NotificationTrait;
      use RemoveTraite;

    public function __construct() {

        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:read_instructors')->only(['index','show']);
        $this->middleware('DashboardPermission:create_instructors')->only(['create','store']);
        $this->middleware('DashboardPermission:update_instructors')->only(['edit','update']);
        $this->middleware('DashboardPermission:delete_instructors')->only(['destroy','MultiDelete']);
    }

    public function index()
    {
        $instructors=Instructor::with('courses')->get();
        return view('dashboard.instructors.index',compact('instructors'));
    }

    public function create()
    {
        return view('dashboard.instructors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:191',
            'title'=>'required|string|max:191',
            'email'=>'required|email|max:191|unique:instructors,email',
            'phone'=>'required|numeric|unique:instructors,phone',
            'photo'=>'required|image:png,jpg,jpeg'
        ]);
//        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
//        if (!preg_match($regex, $request->email)) {
//            return redirect()->back()->withInput()->withErrors('error in email validation');
//        }

        if (!filter_var($request->email,FILTER_VALIDATE_EMAIL)){
            return redirect()->back()->withInput()->withErrors('error_in _email');
        }
        $validated_data=$request->all();
        $request->file('photo')->storeAs(self::_PATH,$request->file('photo')->hashName());
        $validated_data['photo']=$request->file('photo')->hashName();
        $instructor=Instructor::create($validated_data);
        removeCache('instructor');
        if ($instructor){
            toast('Instructor Added Successfully','success',AlertPosition());
            return redirect()->route('instructor.index');
        }else{
            toast('Failed To Add Instructor','error',AlertPosition());
            return redirect()->route('instructor.create');
        }

    }

    public function show($id)
    {
        $courses=Course::with(['instructor','department','department.university'])->where('instructor_id',$id)->get();
        if (count($courses)>0)
        return view('dashboard.instructors.show',compact('courses'));
        else{
            toast('Instructor Dont Have Any Courses','warning',AlertPosition());
            return redirect()->route('instructor.index');
        }
    }

    public function edit($id)
    {

        $instructor=Instructor::find($id);
        if ($instructor){
            return view('dashboard.instructors.edit',compact('instructor'));
        } else{
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect(route('instructor.index'));
        }

    }

    public function update(Request $request, $id)
    {
        $instructor=Instructor::find($id);
        if (!$instructor){
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect(route('instructor.index'));
        }
        $request->validate([
            'name'=>'required|string|max:191',
            'title'=>'required|string|max:191',
            'email'=>'required|email|max:191|unique:instructors,email,'.$id,
            'phone'=>'required|numeric|unique:instructors,phone,'.$id,
            'photo'=>'image:png,jpg,jpeg'
        ]);
//        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
//        if (!preg_match($regex, $request->email)) {
//            return redirect()->back()->withInput()->withErrors('error in email validation');
//        }
        if (!filter_var($request->email,FILTER_VALIDATE_EMAIL)){
            return redirect()->back()->withInput()->withErrors('error_in _email');
        }
            $validated_data=$request->all();
        if ($request->hasFile('photo')){
            Storage::disk('local')->delete(self::_PATH.DIRECTORY_SEPARATOR.$instructor->photo);
            $request->file('photo')->storeAs(self::_PATH,$request->file('photo')->hashName());
            $validated_data['photo']=$request->file('photo')->hashName();
        } else{
            $validated_data['photo']=$instructor->photo;
        }

        $instructor=$instructor->update($validated_data);
        if ($instructor){
            removeCache('instructor');
            toast('Instructor Updated Successfully','success',AlertPosition());
            return redirect()->route('instructor.index');
        }
        toast('Failed To Update Instructor','error',AlertPosition());
        return redirect()->route('instructor.index');
    }

    public function destroy($id)
    {
        //remove instructor will remove  courses  he explain  it
        $instructor=Instructor::find($id);
        if ($instructor){
            Storage::disk('local')->delete(self::_PATH.DIRECTORY_SEPARATOR.$instructor->photo);
            $instructor->delete();
            self::RemoveInstructor($instructor);
            removeCache('instructor');
            toast('Instructor Deleted Successfully','success',AlertPosition());
            return redirect()->route('instructor.index');
        }else{
            toast('Failed To Delete Instructor','error',AlertPosition());
            return redirect()->route('instructor.index');
        }
    }

    public function MultiDelete(Request $request)
    {
        //remove instructor will remove  courses  he explain  it
        $request->validate([
            'instructors_id'=>'required|array|min:1'
        ]) ;

        foreach ($request->instructors_id as $id){
            $instructor=Instructor::find($id);
            if ($instructor){
                Storage::disk('local')->delete(self::_PATH.DIRECTORY_SEPARATOR.$instructor->photo);
                self::RemoveInstructor($instructor);
                $instructor->delete();
            } else{
                continue;
            }
        }
        removeCache('instructor');
        toast('Selected Instructors Deleted Successfully','success',AlertPosition());
        return redirect()->route('instructor.index');
    }

}
