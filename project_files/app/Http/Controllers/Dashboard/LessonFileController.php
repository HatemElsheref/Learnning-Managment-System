<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Lesson;
use App\LessonFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LessonFileController extends Controller
{

    CONST _PATH='courses_files';


    public function __construct() {

        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:create_files')->only('store');
        $this->middleware('DashboardPermission:update_files')->only('update');
        $this->middleware('DashboardPermission:delete_files')->only(['destroy','MultiDelete']);
    }

    public function store(Request $request)
    {

        $request->validate([
            'file_name'=>['required','string','max:191', Rule::unique('lesson_files','name')->where('lesson_id',$request->lesson_id)],
            'lesson_id'=>'required|numeric|min:1',
            'hosting'=>'required|in:local,cloud,drive'
        ]);
        $lesson=Lesson::find($request->lesson_id);
        if (!$lesson){
            toast('Undefined Lesson','error',AlertPosition());
            return redirect()->back();
        }

        $validated_data['name']=$request->file_name;
        $validated_data['lesson_id']=$request->lesson_id;
        $validated_data['hosting']=$request->hosting;
        if ($request->has('status') and $request->status=='published'){
            $validated_data['isFree']=true;
        }else{
            $validated_data['isFree']=false;
        }

        if ($request->hosting=='local'){
            $request->validate([
//                'file'=>'required|mimes:pdf,pptx,ppt,docx,doc|max:2000'
//                 'file'=>'max:2000'
            ]);

            if ($request->hasFile('file') and $request->file('file') != null) {
                $allowed=['pdf','pptx','ppt','docx','doc','php','java','class','txt','py','js','aspx','zip','rar','html'];
                $extension= $request->file('file')->getClientOriginalExtension();
                if (!in_array($extension,$allowed)){
                    return redirect()->back()->withInput()->withErrors('Not Allowed File Type');
                }
                $newName=  Str::random(30).'.'.$extension;
                $request->file('file')->storeAs($this->getCoursePath($lesson->part->course->id).DIRECTORY_SEPARATOR.'files',$newName);
                $validated_data['type']= Storage::disk('local')->mimeType($this->getCoursePath($lesson->part->course->id).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$newName);
                $validated_data['path']=$newName;
            }else{
                return redirect()->back()->withErrors('No Attached File Exist');
            }
        }elseif ($request->hosting=='drive'){
            $request->validate([
                'file'=>'required|url'
            ]);
            $validated_data['type']='Drive';
            $validated_data['path'] = $request->file;
        }
        else{
            $request->validate([
                'file'=>'required|url'
            ]);
            $extension=explode('.', $request->file);
            $extension=end($extension);
            $validated_data['type']=$extension;
            $validated_data['path'] = $request->file;
        }

        $lessonFile=LessonFile::create($validated_data);
        removeCache('course');
        if ($lessonFile){
            toast('Lesson File Added Successfully','success',AlertPosition());
            return redirect()->route('lesson.show',$lesson->id);
        }else{
            toast('Failed To Add Lessons','error',AlertPosition());
            return redirect()->route('lesson.show',$lesson->id);
        }



//
//        if ($request->hasFile('file') and $request->file('file') != null)    {
//
//            $request->file('file')->storeAs($this->getCoursePath($lesson->part->course->id).DIRECTORY_SEPARATOR.'files',$request->file('file')->hashName());
//            $validated_data['type']= Storage::disk('local')->mimeType($this->getCoursePath($lesson->part->course->id).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$request->file('file')->hashName());
//            $validated_data['path']=$request->file('file')->hashName();
//            $lessonFile=LessonFile::create($validated_data);
//            if ($lessonFile){
//                toast('Lesson File Added Successfully','success',AlertPosition());
//                return redirect()->route('lesson.show',$lesson->id);
//            }else{
//                toast('Failed To Add Lessons','error',AlertPosition());
//                return redirect()->route('lesson.show',$lesson->id);
//            }
//        }
//        toast('Failed To Add Lessons','error',AlertPosition());
//        return redirect()->route('lesson.show',$lesson->id);

    }

    public function update(Request $request, $id)
    {
//                   dd($request->all());
        $lessonFile=LessonFile::find($id);
        if (!$lessonFile){
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect()->back();
        }
        $lesson=Lesson::find($request->lesson_id);
        if (!$lesson){
            toast('Undefined Lesson','error',AlertPosition());
            return redirect()->back();
        }

        $request->validate([
            'file_name'=>['required','string','max:191', Rule::unique('lesson_files','name')->where('lesson_id',$request->lesson_id)->ignore($id)],
            'lesson_id'=>'required|numeric|min:1',
            'hosting'=>'required|in:local,cloud,drive'
            ]);

        $validated_data['name']=$request->file_name;
        $validated_data['lesson_id']=$request->lesson_id;

        if ($request->has('status') and $request->status=='published'){
            $validated_data['isFree']=true;
        }else{
            $validated_data['isFree']=false;
        }

        if ($request->hosting=='local'){
            $request->validate([
//                'file'=>'mimes:pdf,pptx,ppt,docx,doc|max:2000'
//                   'file'=>'max:2000'
            ]);

            if ($request->hasFile('file')) {
                $allowed=['pdf','pptx','ppt','docx','doc','php','java','class','txt','py','js','aspx','zip','rar','html'];
                $extension= $request->file('file')->getClientOriginalExtension();
                if (!in_array($extension,$allowed)){
                    return redirect()->back()->withInput()->withErrors('Not Allowed File Type');
                }
                $newName=  Str::random(30).'.'.$extension;
                Storage::disk('local')->delete($this->getCoursePath($lesson->part->course->id) . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $lessonFile->path);
                $request->file('file')->storeAs($this->getCoursePath($lesson->part->course->id) . DIRECTORY_SEPARATOR . 'files', $newName);
                $validated_data['type'] = Storage::disk('local')->mimeType($this->getCoursePath($lesson->part->course->id) . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $newName);
                $validated_data['path'] = $newName;
                $validated_data['hosting']=$request->hosting;
            }else {
                if ($lessonFile->hosting=='local'){      //old value in this case
                    $validated_data['path'] = $lessonFile->path;
                    $validated_data['hosting']=$request->hosting;
                }else{
                    return redirect()->back()->withErrors('You Must Upload File In Local Mode');
                }
            }
        }elseif ($request->hosting=='drive'){
            $request->validate([
                'file'=>'required|url'
            ]);
            $validated_data['type']='Drive';
            $validated_data['path'] = $request->file;
            if ($lessonFile->hosting=='local'){
                Storage::disk('local')->delete($this->getCoursePath($lesson->part->course->id) . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $lessonFile->path);
            }
        }
        else{
            $request->validate([
                'file'=>'required|url'
            ]);
            $extension=explode('.', $request->file);
            $extension=end($extension);
            $validated_data['type']=$extension;
            $validated_data['path'] = $request->file;
            $validated_data['hosting']=$request->hosting;

            if ($lessonFile->hosting=='local'){
                Storage::disk('local')->delete($this->getCoursePath($lesson->part->course->id) . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $lessonFile->path);
            }
        }



        removeCache('course');

            if ($lessonFile->update($validated_data)){
                toast('Lesson File Updated Successfully','success',AlertPosition());
                return redirect()->route('lesson.show',$lesson->id);
            }

                toast('Failed To Update Lesson File','error',AlertPosition());
                return redirect()->route('lesson.show',$lesson->id);

    }

    public function destroy($id)
    {
        $lessonFile=LessonFile::find($id);
        if ($lessonFile){
            $lesson=Lesson::find($lessonFile->lesson_id);
            $course_id=$lesson->part->course_id;
            if ($lessonFile->hosting=='local'){
                Storage::disk('local')->delete($this->getCoursePath($course_id) . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $lessonFile->path);
            }
              $lessonFile->delete();
            removeCache('course');
            toast('Lesson Attached File Deleted Successfully','success',AlertPosition());
            return redirect()->route('lesson.show',$lesson->id);
        }    else{
            toast('Failed To Delete Lesson Attached File','error',AlertPosition());
            return redirect()->back();
        }
    }

    public function MultiDelete(Request $request)
    {
        $request->validate([
            'lesson_file_id'=>'required|array|min:1'
        ]) ;

        $lessonFile=LessonFile::find($request->lesson_file_id[0]);
        $lesson=Lesson::find($lessonFile->lesson_id);
        $course_id=$lesson->part->course_id;
        foreach ($request->lesson_file_id as $id){
            $lessonFile=LessonFile::find($id);
            if ($lessonFile){
                if ($lessonFile->hosting=='local'){
                    Storage::disk('local')->delete($this->getCoursePath($course_id) . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $lessonFile->path);
                }
                $lessonFile->delete();
            }    else{
                continue;
            }

        }
        removeCache('course');
        toast('Lesson Attached File Deleted Successfully','success',AlertPosition());
        return redirect()->route('lesson.show',$lesson->id);

    }

    private function getCoursePath($courseid){
        return  self::_PATH.DIRECTORY_SEPARATOR.$courseid;
    }
}
