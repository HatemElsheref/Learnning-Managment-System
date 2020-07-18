<?php

namespace App\Http\Controllers\Dashboard;

use App\CourseFile;
use App\Http\Controllers\Controller;
use App\Course;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ExamController extends Controller
{

    CONST _PATH='courses_files';

    public function __construct() {

        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:read_exams')->only('show');
        $this->middleware('DashboardPermission:create_exams')->only(['create','store']);
        $this->middleware('DashboardPermission:update_exams')->only(['edit','update']);
        $this->middleware('DashboardPermission:delete_exams')->only(['destroy','MultiDelete']);
    }
    public function show($id){
        $course=Course::find($id);
        if ($course){
            return view('dashboard.exams.show')->with('course',$course);
        }
        else{
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return redirect()->route('dashboard');
        }
    }

    public function create($id){
        $course=Course::find($id);
        if ($course){
            $users=User::all();
            return view('dashboard.exams.create',compact('course'))->with(['users'=>$users]);
        }
        toast('Course Not Found ','error',AlertPosition());
        return redirect()->route('course.index');
    }

    public function store(Request $request)
    {

        $request->validate([
            'course_id'=>'required|numeric|min:0',
            'exam_type'=>'required|in:final,mta,tma',
            'term'=>'required|in:spring,summer,fall',
            'year'=>'required|date_format:Y',
            'hosting'=>'required|in:local,cloud,drive'
        ]);


        $course=Course::find($request->course_id);
        if (!$course){
            toast('Undefined Course','error',AlertPosition());
            return redirect()->route('exams.create',$course->id);

        }
        if (!in_array($request->year,getYears())){
            toast('Undefined Year','error',AlertPosition());
            return redirect()->back();
        }

        $validated_data['type']=$request->exam_type;
        $validated_data['term']=$request->term;
        $validated_data['year']=$request->year;
        $validated_data['course_id']=$request->course_id;
        $validated_data['hosting']=$request->hosting;

        if ($request->has('status') and $request->status=='opened'){
            $validated_data['status']='opened';

        }else{
            $validated_data['status']='closed';
            $request->validate(['users'=>'required|array|min:1']);
            $validated_data['shared']=json_encode($request->users);
        }

        if ($request->hosting=='local'){


            $allowed=['pdf','pptx','ppt','docx','doc','php','java','class','txt','py','js','aspx','zip','rar','html'];
            $request->validate([
//                'exam'=>'require/d|mimes:pdf,pptx,ppt,docx,doc|max:2000'
//                    'exam'=>'max:2000'
            ]);
            $extension= $request->file('exam')->getClientOriginalExtension();
            if (!in_array($extension,$allowed)){
                   return redirect()->back()->withInput()->withErrors('Not Allowed File Type');
            }
            if ($request->hasFile('exam') and $request->file('exam') != null) {
                $newName=  Str::random(30).'.'.$extension;
                $request->file('exam')->storeAs($this->getCoursePath($course->id), $newName);
                $validated_data['path'] = $newName;
            }else{
                toast('Failed To Add Exam','error',AlertPosition());
                return redirect()->route('exams.create',$course->id);
            }
        }else{
            $request->validate([
                'exam'=>'required|url'
            ]);
            $validated_data['path'] = $request->exam;
        }

        $courseFile=CourseFile::create($validated_data);
        if ($courseFile){
            removeCache('course');
            toast('Exam File Added Successfully','success',AlertPosition());
            return redirect()->route('exams.show',$course->id);
        }else{
            toast('Failed To Add Exam','error',AlertPosition());
            return redirect()->route('exams.create',$course->id);
        }

    }

    public function edit($id){
        $courseFile=CourseFile::find($id);
        if (!$courseFile){
             toast('In Correct Id Exam Not Found','error',AlertPosition());
             return redirect()->route('dashboard');
        }
        $users=User::all();
        return view('dashboard.exams.edit',compact('courseFile'))->with(['users'=>$users]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'exam_type'=>'required|in:final,mta,tma',
            'term'=>'required|in:spring,summer,fall',
            'year'=>'required|date_format:Y',
            'hosting'=>'required|in:local,cloud,drive'
        ]);

        if (!in_array($request->year,getYears())){
            toast('Undefined Year','error',AlertPosition());
            return redirect()->back();
        }
        $courseFile=CourseFile::find($id);
        if (!$courseFile){
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect()->back();
        }

        $course_id=$courseFile->course_id;
        $validated_data['type']=$request->exam_type;
        $validated_data['term']=$request->term;
        $validated_data['year']=$request->year;
        $validated_data['course_id']=$course_id;

        if ($request->has('status') and $request->status=='opened'){
            $validated_data['status']='opened';
            $validated_data['shared']='';

        }else{
            $validated_data['status']='closed';
            $request->validate(['users'=>'required|array|min:1']);
            $validated_data['shared']=json_encode($request->users);
        }

        if ($request->hosting=='local'){
            $request->validate([
//                'exam'=>'mimes:pdf,pptx,ppt,docx,doc,php,java,txt,py,js,aspx,zip,rar|max:2000'
//                'exam'=>'max:2000'
            ]);

            if ($request->hasFile('exam') and $request->file('exam') != null) {
                $allowed=['pdf','pptx','ppt','docx','doc','php','javas','class','txt','py','js','aspx','zip','rar','html'];
                $extension= $request->file('exam')->getClientOriginalExtension();
                if (!in_array($extension,$allowed)){
                    return redirect()->back()->withInput()->withErrors('Not Allowed File Type');
                }
                Storage::disk('local')->delete($this->getCoursePath($course_id).$courseFile->path);
                $newName=  Str::random(30).'.'.$extension;
                $request->file('exam')->storeAs($this->getCoursePath($course_id),  $newName);
                $validated_data['path'] = $newName;


                $validated_data['hosting']=$request->hosting;
            }else{
                if ($courseFile->hosting=='local'){
                    $validated_data['path'] = $courseFile->path;
                    $validated_data['hosting']=$request->hosting;
                }else{
                    return redirect()->back()->withErrors('You Must Upload File In Local Mode');
                }
            }
        }elseif($request->hosting=='drive'){
            $request->validate([
                'exam'=>'required|url'
            ]);
            $validated_data['path'] = $request->exam;
            $validated_data['hosting']=$request->hosting;
            if ($courseFile->hosting=='local'){
                Storage::disk('local')->delete($this->getCoursePath($course_id).$courseFile->path);
            }
        } else{
            $request->validate([
                'exam'=>'required|url'
            ]);
            $validated_data['path'] = $request->exam;
            $validated_data['hosting']=$request->hosting;
            if ($courseFile->hosting=='local'){
                Storage::disk('local')->delete($this->getCoursePath($course_id).$courseFile->path);
            }
        }

        if ($courseFile->update($validated_data)){
            removeCache('course');
            toast('Exam Updated Successfully','success',AlertPosition());
            return redirect()->route('exams.show',$courseFile->course_id);
        }

        toast('Exam To Update Lesson File','error',AlertPosition());
        return redirect()->back();

    }

    public function destroy($id)
    {
        $courseFile=CourseFile::find($id);
        if ($courseFile){
            $course_id=$courseFile->course_id;
            if ($courseFile->hosting=='local'){
                Storage::disk('local')->delete($this->getCoursePath($course_id). DIRECTORY_SEPARATOR . $courseFile->path);
            }
            $courseFile->delete();
            removeCache('course');
            toast('Exam  Deleted Successfully','success',AlertPosition());
            return redirect()->back();
        }    else{
            toast('Failed To Delete Exam','error',AlertPosition());
            return redirect()->back();
        }
    }

    public function MultiDelete(Request $request)
    {
        $request->validate([
            'exams_id'=>'required|array|min:1'
        ]) ;

        foreach ($request->exams_id as $id){
            $courseFile=CourseFile::find($id);
            if ($courseFile){
                $course_id=$courseFile->course_id;
                if ($courseFile->hosting=='local'){
                    Storage::disk('local')->delete($this->getCoursePath($course_id). DIRECTORY_SEPARATOR . $courseFile->path);
                }
                $courseFile->delete();
            }    else{
                continue;
            }
        }
        removeCache('course');
        toast('Selected Exam  Deleted Successfully','success',AlertPosition());
        return redirect()->back();
    }

    private function getCoursePath($courseid){
        return  self::_PATH.DIRECTORY_SEPARATOR.$courseid.DIRECTORY_SEPARATOR.'exams'.DIRECTORY_SEPARATOR;
    }
}
