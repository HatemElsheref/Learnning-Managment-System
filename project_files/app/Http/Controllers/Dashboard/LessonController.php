<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Course;
use App\CourseFile;
use App\Http\Controllers\Controller;
use App\Lesson;
use App\LessonFile;
use App\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LessonController extends Controller
{
    CONST _PATH='courses_files';

    use NotificationTrait;
     use RemoveTraite;


    public function __construct() {

        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:read_files')->only('show');
        $this->middleware('DashboardPermission:create_lessons')->only(['create','store']);
        $this->middleware('DashboardPermission:update_lessons')->only(['edit','update']);
        $this->middleware('DashboardPermission:delete_lessons')->only(['destroy','MultiDelete']);
        $this->middleware('DashboardPermission:read_lesson_reviews')->only('Reviews');
        $this->middleware('DashboardPermission:delete_lesson_reviews')->only(['destroyReview','multiDeleteReviews']);
    }

    public function show($id){
        $lesson=Lesson::with('files')->find($id);
        if (!$lesson) {
            toast('Lesson Not Found', 'error', AlertPosition());
            return redirect()->route('dashboard');
        }
        return view('dashboard.lessons.show',compact('lesson'));
    }

    public function create($id)
    {
        $course=Course::with('parts')->find($id);
        if ($course){
            return view('dashboard.lessons.create',compact('course'));
        }
        toast('Course Not Found','error',AlertPosition());
        return redirect()->route('dashboard');
    }

    public function store(Request $request)
    {

//        dd($request->all());
        $request->validate([
            'name'=>['required','string','max:191', Rule::unique('lessons','name')->where('part_id',$request->part_id)],
            'part_id'=>'required|numeric|min:1',
            'file'=>'mimes:pdf,pptx,ppt,docx,doc|max:2000',
            'hosting'=>'required|in:youtube,local,cloud,drive'
        ]);
        $course=Course::find($request->course_id);
        if (!$course){
            toast('Undefined Course','error',AlertPosition());
            return redirect()->route('dashboard');
        }
        $part=Part::find($request->part_id);
        if (!$part){
            toast('Undefined Part In This Course','error',AlertPosition());
            return redirect()->route('course.show',$course->id);
        }

        $validated_data['name']=$request->name;
        $validated_data['part_id']=$request->part_id;
        $validated_data['type']=$request->hosting;

        if ($request->has('status') and $request->status=='published'){
            $validated_data['status']=true;
        }else{
            $validated_data['status']=false;
        }

        if ($request->hosting=='youtube') {
            $request->validate(['video' => 'required|string|max:191']);
            $validated_data['video'] = $request->video;
        }elseif ($request->hasFile('video') and !empty($request->file('video')) and $request->file('video')!=null) {
            $request->validate([
//                'video'=>'required|mimes:mp4,mov,ogg,mkv|max:30000'
                'video'=>'required|mimes:mp4,mov,ogg,mkv'
            ]);
            $request->file('video')->storeAs($this->getCoursePath($request->course_id),$request->file('video')->hashName());
            $validated_data['video']=$request->file('video')->hashName();
        } elseif($request->hosting=='cloud' or $request->hosting=='drive'){
            $request->validate([
                'video'=>'required|url'
            ]);
            $validated_data['video']=$request->video;
        }

        $lesson=Lesson::create($validated_data);
        if ($lesson){
            if ($request->hasFile('file') and $request->file('file') != null)    {

                $request->file('file')->storeAs($this->getCoursePath($request->course_id).DIRECTORY_SEPARATOR.'files',$request->file('file')->hashName());
                $mime= Storage::disk('local')->mimeType($this->getCoursePath($request->course_id).DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$request->file('file')->hashName());
                $path=$request->file('file')->hashName();
                LessonFile::create([
                    'name'=>$lesson->name,
                    'path'=>$path,
                    'type'=>$mime,
                    'lesson_id'=>$lesson->id
                ]);

            }
            removeCache('course');
            removeCache('orders');
            toast('Lesson Added Successfully','success',AlertPosition());
//            return redirect()->route('lesson.create',$request->course_id);
            return redirect()->route('course.show',$course->id);
        }else{
            toast('Failed To Add Lessons','error',AlertPosition());
            return redirect()->route('lesson.create',$request->course_id);
        }

    }

    public function edit($id)
    {
        $lesson=Lesson::with('part')->find($id);

        $course=$lesson->part->course;
        $parts=Part::where('course_id',$course->id)->get();
        if ($lesson){
            return view('dashboard.lessons.edit',compact('lesson'))->with('parts',$parts);
        }
        toast('Lesson Not Found','error',AlertPosition());
        return redirect()->route('course.index');

    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name'=>['required','string','max:191', Rule::unique('lessons','name')->where('part_id',$request->part_id)->ignore($id)],
            'part_id'=>'required|numeric|min:0',
            'hosting'=>'required|in:youtube,local,cloud,drive'

        ]);

        $lesson=Lesson::find($id);
        if (!$lesson){
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect(route('course.index'));
        }
        $course=Course::find($request->course_id);

        if (!$course){
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect(route('course.index'));
        }

        $part=Part::find($request->part_id);
        if (!$part){
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect(route('course.show',$course->id));
        }


         $validated_data=$request->except('course_id');

        $validated_data['name']=$request->name;
        $validated_data['part_id']=$request->part_id;
        $validated_data['type']=$request->hosting;


        if ($request->has('status') and $request->status=='published'){
            $validated_data['status']=true;
        }else{
            $validated_data['status']=false;
        }



        if ($request->hosting=='youtube') {

            $request->validate([
                'video'=>'required|string|max:191'
            ]);
            if ($request->has('video') and !empty($request->video) and $request->video!=null) {
                $validated_data['video']=$request->video;
            }   else{
                return redirect()->route('lesson.edit',$lesson->id)->withInput()->withErrors('Invalid Video Embed Path');
            }

        }
        elseif($request->hosting=='local'){

            $request->validate([
//                'video'=>'mimes:mp4,mov,ogg,mkv|max:30000'
                'video'=>'mimes:mp4,mov,ogg,mkv'
            ]);

            if ($request->hasFile('video') and !empty($request->file('video')) and $request->file('video')!=null) {

                Storage::disk('local')->delete($this->getCoursePath($request->course_id).DIRECTORY_SEPARATOR.$lesson->video);
                $request->file('video')->storeAs($this->getCoursePath($request->course_id),$request->file('video')->hashName());
                $validated_data['video']=$request->file('video')->hashName();
            }   else{
                $validated_data['video']=$lesson->video;
            }

        } elseif($request->hosting=='cloud' or $request->hosting=='drive'){
            $request->validate([
                'video'=>'required|url'
            ]);
            if ($request->has('video') and !empty($request->video) and $request->video!=null) {
                if ( filter_var($request->video,FILTER_VALIDATE_URL)==false){
                    return redirect()->route('lesson.edit',$lesson->id)->withInput()->withErrors('Invalid Video Url');
                }
                $validated_data['video']=$request->video;
            }   else{
                return redirect()->route('lesson.edit',$lesson->id)->withInput()->withErrors('Invalid Video Url');

            }
        }

        if ($lesson->update($validated_data)){
            removeCache('course');
            removeCache('orders');
            toast('Lesson Updated Successfully','success',AlertPosition());
            return redirect()->route('course.show',$course->id);
        }
        toast('Failed To Update Lesson','error',AlertPosition());
        return redirect()->route('course.show',$course->id);
    }

    public function destroy($id)
    {

        $lesson=Lesson::find($id);

        if ($lesson){
            $course=$lesson->part->course;
            if ($lesson->type=='local'){      //hosting == type
                Storage::disk('local')->delete($this->getCoursePath($lesson->course_id).DIRECTORY_SEPARATOR.$lesson->video);
            }
            self::RemoveLesson($lesson);
            $lesson->delete();
            removeCache('course');
            removeCache('orders');
            toast('Lesson Deleted Successfully','success',AlertPosition());
            return redirect()->route('course.show',$course->id);
        }else{
            toast('Failed To Delete Lesson Lesson Not Found','error',AlertPosition());
            return redirect()->route('dashboard');
        }
    }

    public function MultiDelete(Request $request)
    {
        $request->validate([
            'lessons_id'=>'required|array|min:1'
        ]) ;
        $course=null;
        foreach ($request->lessons_id as $id){
            $lesson=Lesson::find($id);
            if ($lesson){
                $course=$lesson->part->course;
                if ($lesson->type=='local'){
                    Storage::disk('local')->delete($this->getCoursePath($lesson->course_id).DIRECTORY_SEPARATOR.$lesson->video);
                }
                self::RemoveLesson($lesson);
                $lesson->delete();
            } else{
                continue;
            }
        }
        removeCache('course');
        removeCache('orders');
        toast('Selected Lessons Deleted Successfully','success',AlertPosition());
        return redirect()->route('course.show',$course->id);
    }

    private function getCoursePath($courseid){
        return  self::_PATH.DIRECTORY_SEPARATOR.$courseid;
    }



    public function Reviews($lessonId){
        $lesson=Lesson::find($lessonId);
        if ($lesson){
            $reviews=DB::table('rate_lessons')->select(['rate_lessons.*','users.name'])
                ->join('users','rate_lessons.user_id','=','users.id')
                ->join('lessons','rate_lessons.lesson_id','=','lessons.id')
                ->where('lesson_id','=',$lesson->id)->orderByDesc('created_at')->get();
            removeCache('course');
            removeCache('orders');
            return view('dashboard.lessons.reviews',['reviews'=>$reviews,'lesson'=>$lesson]);
        }
    }
    public function destroyReview($reviewId){
        DB::table('rate_lessons')->where('id','=',$reviewId)->delete();
        removeCache('course');
        removeCache('orders');
        toast('Review Deleted Successfully','success',AlertPosition());
        return redirect()->back();
    }
    public function multiDeleteReviews(Request $request){
        $request->validate(
            ['reviews_id'=>'required|array|min:1']
        );
        foreach($request->reviews_id as $id){
            DB::table('rate_lessons')->where('id','=',$id)->delete();
        }
        removeCache('course');
        removeCache('orders');
        toast('Selected Reviews Deleted Successfully','success',AlertPosition());
        return redirect()->back();
    }






}
