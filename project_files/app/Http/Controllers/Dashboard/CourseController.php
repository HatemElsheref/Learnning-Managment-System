<?php

namespace App\Http\Controllers\Dashboard;

use App\course;
use App\Department;
use App\Part;
use App\University;
use App\Http\Controllers\Controller;
use App\Instructor;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class CourseController extends Controller
{
    CONST _PATH='courses_files';

    use NotificationTrait;
    use RemoveTraite;

    public function __construct() {

        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:read_photos')->only('photos');
        $this->middleware('DashboardPermission:read_courses')->only(['index','show']);
        $this->middleware('DashboardPermission:create_courses')->only(['create','store']);
        $this->middleware('DashboardPermission:update_courses')->only(['edit','update','seo']);
        $this->middleware('DashboardPermission:delete_courses')->only(['destroy','MultiDelete']);
        $this->middleware('DashboardPermission:create_parts')->only('addPart');
        $this->middleware('DashboardPermission:update_parts')->only('updatePart');
        $this->middleware('DashboardPermission:delete_parts')->only('deletePart');
        $this->middleware('DashboardPermission:read_course_reviews')->only('Reviews');
        $this->middleware('DashboardPermission:delete_course_reviews')->only(['destroyReview','multiDeleteReviews']);
    }

    public function index()
    {
        $courses=Course::with(['instructor','department','department.university'])->orderBy('created_at','desc')->get();
        return view('dashboard.course.index',compact('courses'));
    }

    public function create()
    {
        $universities=University::all();
        if (count($universities)>0){
            $instructors=Instructor::all();
            if (count($instructors)>0){
                return view('dashboard.course.create',['instructors'=>$instructors,'universities'=>$universities]);
            } else{
                toast('You Must Add Instructor First','info',AlertPosition());
                return redirect()->route('instructor.create');
            }
        } else{
            toast('You Must Add University And Department First','info',AlertPosition());
            return redirect()->route('department.create');
        }

    }

    public function show($id){
        $course=Course::with('parts','parts.lessons','parts.lessons.files','files')
            ->find($id);

        if ($course){
         $users=DB::table('course_user')->where('course_id','=',$course->id)
             ->where('status','=','opened')->count();

            $photos=DB::table('uploads')
                ->where('parent','=','course')
                ->where('parent_id','=',$course->id)
                ->where('mimes','=','image')->count();
            return view('dashboard.course.show')
                ->with('course',$course)
                ->with('photos',$photos)
                ->with('users',$users);
        }
        else{
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return redirect()->route('course.index');
        }
    }

    public function photos($id){
        $course=Course::find($id);
        if ($course){
            $photos=DB::table('uploads')->select('*')
                ->where('parent','=','course')
                ->where('parent_id','=',$course->id)
                ->where('mimes','=','image')->get();
            return view('dashboard.course.photos')
                ->with('course',$course)
                ->with('photos',$photos);
        }
        else{
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {

        $request->validate([
            'name'=>'required|string|max:191',
            'code'=>'required|string|max:191|unique:courses,code',
            'description'=>'required|string',
            'intro'=>'required|in:youtube,local',
            'instructor_id'=>'required|numeric',
            'department_id'=>'required|numeric',
            'slug'=>'required|string|max:191|unique:courses,slug',
            'photo'=>'required|image:png,jpg,jpeg',
            'parts'=>'required|array|min:1'
//            'price'=>'numeric|min:0',
//            'video'=>'required|mimes:mp4,mov,ogg|max:20000',
        ]);

        if (!Department::find($request->department_id)){
            toast('Not Found , Determine Correct Department Id','error',AlertPosition());
            return  redirect(route('course.create'));
        }

        if (!Instructor::find($request->instructor_id)){
            toast('Not Found , Determine Correct Instructor Id','error',AlertPosition());
            return  redirect(route('course.create'));
        }
        $validated_data=$request->except('parts');

        if ($request->intro=='youtube') {
            $request->validate([
                'video'=>'required|string|max:191'
            ]);
            $validated_data['video']=$request->video;
        }   elseif($request->intro=='local'){
            $request->validate([
//                'video'=>'required|mimes:mp4,mov,ogg|max:30000'
                'video'=>'required|mimes:mp4,mov,ogg,mkv'
            ]);
            $validated_data['video']='intro_'.$request->file('video')->hashName();
        }

        if (!$request->has('price') or $request->price==null){
            $validated_data['price']=0;
        }  else{
            if (is_numeric($request->price) and $request->price>=0){
                $validated_data['price']=$request->price;
            }else{
                $validated_data['price']=0;
            }
        }

        $parts_name=[];
        foreach ($request->parts as $part){
            if (($part==null) or (empty($part))){
                return redirect()->back()->withInput()->withErrors('Enter Correct Part');
            }else{
                array_push($parts_name,$part);
            }
        }

        $validated_data['photo']='intro_'.$request->file('photo')->hashName();
        $course=course::create($validated_data);

        if ($course){
            Cache::forget('universities');
            Cache::forget('departments');
            Cache::forget('topCourses');
            Cache::forget('recent_courses');
            Cache::forget('total_courses');
            removeCache('course');
            foreach (array_unique($parts_name) as $name){
                Part::create(['name'=>$name,'course_id'=>$course->id]);
            }
            $request->file('photo')->storeAs($this->getCoursePath($course->id).DIRECTORY_SEPARATOR.'photos','intro_'.$request->file('photo')->hashName());

            if ($course->intro=='local'){
                $request->file('video')->storeAs($this->getCoursePath($course->id),'intro_'.$request->file('video')->hashName());
                $course->save();
            }

            toast('Course Added Successfully','success',AlertPosition());
            return redirect()->route('course.index');
        }else{
            toast('Failed To Add Course','error',AlertPosition());
            return redirect()->route('course.create');
        }

    }

    public function edit($id)
    {
        $course=Course::with(['parts','department'])->find($id);
        if ($course){
            $universities=University::with('departments')->get();
            if (count($universities)>0){
                $instructors=Instructor::all();
                if (count($instructors)>0){
                    return view('dashboard.course.edit',['instructors'=>$instructors,'universities'=>$universities,'course'=>$course]);
                } else{
                    toast('You Must Add Instructor First','info',AlertPosition());
                    return redirect()->route('instructor.create');
                }
            } else{
                toast('You Must Add Department First','info',AlertPosition());
                return redirect()->route('university.create');
            }
        } else{
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect(route('course.index'));
        }

    }

    public function update(Request $request, $id)
    {


        $course=Course::find($id);
        if (!$course){
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect(route('course.index'));
        }

        $request->validate([
            'name'=>'required|string|max:191',
            'code'=>'required|string|max:191|unique:courses,code,'.$id,
            'description'=>'required',
            'intro'=>'in:youtube,local',
            'price'=>'numeric|min:0',
            'instructor_id'=>'required|numeric',
            'department_id'=>'required|numeric',
            'slug'=>'required|string|max:191|unique:courses,slug,'.$id,
            'photo'=>'image:png,jpg,jpeg',
//            'parts'=>'required|array|min:1'
//            'video'=>'mimes:mp4,mov,ogg|max:20000',

        ]);

        if (!Department::find($request->department_id)){
            toast('Not Found , Determine Correct Department Id','error',AlertPosition());
            return  redirect(route('course.index'));
        }

        if (!Instructor::find($request->instructor_id)){
            toast('Not Found , Determine Correct Instructor Id','error',AlertPosition());
            return  redirect(route('course.index'));
        }

        $validated_data=$request->except('parts');

        if (!$request->has('price') or $request->price==null){
            $validated_data['price']=0;
        }  else{
            if (is_numeric($request->price) and $request->price>=0){
                $validated_data['price']=$request->price;
            }else{
                $validated_data['price']=$course->price;
            }
        }


              if ($request->has('intro')){
                  if ($request->intro=='youtube') {
                      $request->validate([
                          'video'=>'max:191'
                      ]);
                      if (empty($request->video) or $request->video==null){
                          return redirect()->route('course.edit',$course->id)->withInput()->withErrors('Invalid Video Embed Path');
                      }
                      if ($course->intro=='local'){
                          Storage::disk('local')->delete($this->getCoursePath($course->id).DIRECTORY_SEPARATOR.$course->video);
                      }
                      $validated_data['video']=$request->video;
                  }   elseif($request->intro=='local'){
                      $request->validate([
                          'video'=>'mimes:mp4,mov,ogg,mkv'
                      ]);
                      if ($request->hasFile('video')) {
                          if ($course->intro=='local'){
                              Storage::disk('local')->delete($this->getCoursePath($course->id).DIRECTORY_SEPARATOR.$course->video);
                          }
                          $request->file('video')->storeAs($this->getCoursePath($course->id),'intro_'.$request->file('video')->hashName());
                          $validated_data['video']='intro_'.$request->file('video')->hashName();
                          $course->save();
                      } else{
                          $validated_data['video']=$course->video;
                      }
                  }
              }  else{
                  $validated_data['intro']=$course->intro;
                  $validated_data['video']=$course->video;
              }



        if ($request->hasFile('photo')){
            Storage::disk('local')->delete($this->getCoursePath($course->id).DIRECTORY_SEPARATOR.'photos'.DIRECTORY_SEPARATOR.$course->photo);
            $request->file('photo')->storeAs($this->getCoursePath($course->id).DIRECTORY_SEPARATOR.'photos','intro_'.$request->file('photo')->hashName());
            $validated_data['photo']='intro_'.$request->file('photo')->hashName();
        } else{
            $validated_data['photo']=$course->photo;
        }


        $course->update($validated_data);
        Cache::forget('universities');
        Cache::forget('departments');
        Cache::forget('topCourses');
        Cache::forget('recent_courses');
        Cache::forget('total_courses');
        removeCache('course');

        if ($course){
            toast('Course Updated Successfully','success',AlertPosition());
            return redirect()->route('course.index');
        }else{
            toast('Failed To Update Course','error',AlertPosition());
            return redirect()->route('course.create');
        }
    }

    public function destroy($id)
    {
        //remove course will remove all attached parts ans lessons and files and photos and exams in it
        $course=Course::find($id);
        if ($course){
            Storage::disk('local')->delete($this->getCoursePath($course->id).DIRECTORY_SEPARATOR.'photos'.DIRECTORY_SEPARATOR.$course->photo);
            if ($course->intro=='local'){
                if (file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'courses_files'.DIRECTORY_SEPARATOR.$course->id.DIRECTORY_SEPARATOR.$course->video))){
                    Storage::disk('local')->delete($this->getCoursePath($course->id).DIRECTORY_SEPARATOR.$course->video);
                }
            }

            self::RemoveCourse($course);
//            File::deleteDirectory(storage_path('app'.DIRECTORY_SEPARATOR.$this->getCoursePath($course->id)));
            $course->delete();
            Cache::forget('topCourses');
            Cache::forget('recent_courses');
            Cache::forget('total_courses');
            Cache::forget('universities');
            Cache::forget('departments');
            removeCache('course');
            toast('Course Deleted Successfully','success',AlertPosition());
            return redirect()->route('course.index');
        }else{
            toast('Failed To Delete Course','error',AlertPosition());
            return redirect()->route('course.index');
        }
    }

    public function MultiDelete(Request $request)
    {
        //remove course will remove all attached parts ans lessons and files and photos and exams in it
        $request->validate([
            'courses_id'=>'required|array|min:1'
        ]) ;

        foreach ($request->courses_id as $id){
            $course=Course::find($id);
            if ($course){
                Storage::disk('local')->delete($this->getCoursePath($course->id).DIRECTORY_SEPARATOR.'photos'.DIRECTORY_SEPARATOR.$course->photo);
                if ($course->intro=='local'){
                    if (file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'courses_files'.DIRECTORY_SEPARATOR.$course->id.DIRECTORY_SEPARATOR.$course->video))){
                        Storage::disk('local')->delete($this->getCoursePath($course->id).DIRECTORY_SEPARATOR.$course->video);
                    }
                }
                   self::RemoveCourse($course);
                $course->delete();

            } else{
                continue;
            }
        }
        Cache::forget('topCourses');
        Cache::forget('recent_courses');
        Cache::forget('total_courses');
        Cache::forget('universities');
        Cache::forget('orders');
        Cache::forget('departments');
        removeCache('course');
        toast('Selected Courses Deleted Successfully','success',AlertPosition());
        return redirect()->route('course.index');
    }

    public function seo(Request $request,$id){

        $course=Course::find($id);
        if (!$course){
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect()->back();
        }
        $request->validate([
            'slug'=>'required|string|max:191|unique:departments,slug,'.$id,
            'meta_description'=>'required|string',
            'meta_title'=>'required|string|max:191',
            'meta_keywords'=>'required|array|min:1',
        ]);

        $course->meta_title=$request->meta_title;
        $course->slug=$request->slug;
        $course->meta_description=$request->meta_description;
        $course->meta_keywords=json_encode($request->meta_keywords);
        $course->save();
        Cache::forget('topCourses')  ;
        Cache::forget('recent_courses');
        Cache::forget('total_courses');
        Cache::forget('orders');
              Cache::forget('universities');
            Cache::forget('departments');
        removeCache('course');
        toast('Course Seo Updated Successfully','success',AlertPosition());
        return redirect()->route('course.show',$course->id);

    }

    public function getDepartments(Request $request){
        if ($request->ajax()){
            $departments=Department::where('university_id','=',$request->id)->get();
            return response()->json($departments,200);
        }  else{
            return redirect()->route('dashboard');
        }
    }

    private function getCoursePath($courseid){
        return  self::_PATH.DIRECTORY_SEPARATOR.$courseid;
    }

    public function addPart(Request $request){
        $request->validate([
            'part_name'=>['required',Rule::unique('parts','name')->where('course_id',$request->course_id)]
        ]);
        $course= Course::find($request->course_id);
        if ($course){
            Part::create(['name'=>$request->part_name,'course_id'=>$request->course_id]);
            toast('Course Part Added Successfully','success',AlertPosition());
            removeCache('course');
            return redirect()->back();
        }   else{
            toast('Not Found Course In Correct Id','error',AlertPosition());
            return redirect()->back();
        }
    }

    public function updatePart(Request $request,$id){
        $request->validate([
            'part_name'=>['required',Rule::unique('parts','name')->where('course_id',$request->course_id)->ignore($id)]
        ]);
        $part= Part::find($id);
       if ($part){
           $part->name=$request->part_name;
           $part->save();
           removeCache('course');
           toast('Course Part Updated Successfully','success',AlertPosition());
           return redirect()->back();
       }   else{
           toast('Not Found Part In Correct Id','error',AlertPosition());
           return redirect()->back();
       }
    }

    public function deletePart($id){

        $part= Part::find($id);
        if ($part){
            foreach ($part->lessons as $lesson){
                self::RemoveLesson($lesson);
                if ($lesson->type=='local'){
                    Storage::disk('local')->delete(self::Path($lesson->part->course->id).$lesson->video);
                }
                $lesson->delete();
            }
            $part->delete();
            removeCache('course');
            toast('Part And Its Lessons Deleted Successfully','success',AlertPosition());
            return redirect()->route('course.show',$part->course_id);
        }   else{
            toast('Not Found Part In Correct Id','error',AlertPosition());
            return redirect()->back();
        }
    }

    public function Reviews($courseId){
        $course=course::find($courseId);
        if ($course){
            $reviews=DB::table('rate_courses')->select(['rate_courses.*','users.name'])
                ->join('users','rate_courses.user_id','=','users.id')
                ->join('courses','rate_courses.course_id','=','courses.id')
                ->where('course_id','=',$course->id)->orderByDesc('created_at')->get();
            return view('dashboard.course.reviews',['reviews'=>$reviews,'course'=>$course]);
        }
    }

    public function destroyReview($reviewId){
       DB::table('rate_courses')->where('id','=',$reviewId)->delete();
        removeCache('course');
        toast('Review Deleted Successfully','success',AlertPosition());
        return redirect()->back();
    }

    public function multiDeleteReviews(Request $request){
           $request->validate(
               ['reviews_id'=>'required|array|min:1']
           );
           foreach($request->reviews_id as $id){
               DB::table('rate_courses')->where('id','=',$id)->delete();
           }
        removeCache('course');
        toast('Selected Reviews Deleted Successfully','success',AlertPosition());
        return redirect()->back();
    }
}

