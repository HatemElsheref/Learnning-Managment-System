<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
use App\course;
use App\CourseFile;
use App\Http\Controllers\Controller;
use App\Lesson;
use App\RateCourse;
use App\RateLesson;
use App\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class LessonController extends Controller
{
    public function __construct() {
        $this->middleware('auth:web')->only('checkAuthorization');
    }

    private function cache(){
        $data=[];
        $categories=Cache::rememberForever('categories',function (){
            return   Category::with('posts')->get();
        });
        $data['categories']=$categories;
        $universities=Cache::rememberForever('universities',function (){
            return   University::with('departments')->get();
        });
        $data['universities']=$universities;
        return $data;
    }

    public function local($slug){
        $data=$this->cache();
        $data['course']=course::with(['instructor','files','users','parts','department','department.university','parts.lessons','parts.lessons.files'])
            ->where('slug','=',$slug)->first();
        if ($data['course']){
            $data['exams']=CourseFile::where('course_id','=',$data['course']->id)->orderBy('year','desc')
                ->get()->groupBy('type')->toArray() ;
            $data['course_rate']=$this->CalculateCourseRate($data['course']->id);

            $data['rates']=$data['course']->rates()->with('user')->where('status','=','approved')->orderBy('created_at','desc')->limit(4)->get();

            if (auth('web')->check()) {
                $data['user_rate']=RateCourse::where('course_id','=',$data['course']->id)->where('user_id',auth('web')->user()->id)->first();
            } else{
                $data['user_rate']=null;
            }
            return view('frontend.lesson.local_lesson',$data);
        }
        return view('frontend.404');
    }

    public function video($course,$lesson){

        $course=course::with(['instructor','users','department','department.university','parts.lessons'])
            ->where('id','=',$course)->first();
        if ($course){
            $lesson=Lesson::with(['rates','files'])->find($lesson);
            if ($lesson){
                if (in_array($lesson->part->id,$course->parts->pluck('id')->toArray())){
                             if ($this->courseIsFree($course)){
                                 $data=$this->cache();
                                 if ($lesson->type=='youtube' or $lesson->type=='cloud' or  $lesson->type=='drive'){
                                     $data['video']=$lesson->video;
                                 }
                                 $data['course']=$course;
                                 $data['exams']=CourseFile::where('course_id','=',$data['course']->id)->orderBy('year','desc')
                                     ->get()->groupBy('type')->toArray() ;
                                 $data['course_rate']=$this->CalculateCourseRate($data['course']->id);
                                 $data['lesson_rate']=$this->CalculateLessonRate($lesson->id);
                                 $data['rates']=$lesson->rates()->with('user')->where('status','=','approved')->orderBy('created_at','desc')->limit(4)->get();

                                 if (auth('web')->check()) {
                                     $data['user_rate']=RateLesson::where('lesson_id','=',$lesson->id)->where('user_id',auth('web')->user()->id)->first();
                                 } else{
                                     $data['user_rate']=null;
                                 }
                                 $data['lesson']=$lesson;

                                 return view('frontend.lesson.video',$data);
                             } else{

                                    if ($lesson->status){
                                        $data=$this->cache();
                                        if ($lesson->type=='youtube' or $lesson->type=='cloud' or  $lesson->type=='drive'){
                                            $data['video']=$lesson->video;
                                        }
                                        $data['course']=$course;
                                        $data['exams']=CourseFile::where('course_id','=',$data['course']->id)->orderBy('year','desc')
                                            ->get()->groupBy('type')->toArray() ;
                                        $data['course_rate']=$this->CalculateCourseRate($data['course']->id);
                                        $data['lesson_rate']=$this->CalculateLessonRate($lesson->id);
                                        $data['rates']=$lesson->rates()->with('user')->where('status','=','approved')->orderBy('created_at','desc')->limit(4)->get();
                                        if (auth('web')->check()) {
                                            $data['user_rate']=RateLesson::where('lesson_id','=',$lesson->id)->where('user_id',auth('web')->user()->id)->first();
                                        } else{
                                            $data['user_rate']=null;
                                        }
                                        $data['lesson']=$lesson;


                                        return view('frontend.lesson.video',$data);
                                    }  else{

                                        if (auth('web')->check()){

                                                     if (checkIfUserHasThisCourse($course)=='opened'){

                                                         $data=$this->cache();
                                                         if ($lesson->type=='youtube' or $lesson->type=='cloud' or  $lesson->type=='drive'){
                                                             $data['video']=$lesson->video;
                                                         }

                                                         $data['course']=$course;
                                                         $data['exams']=CourseFile::where('course_id','=',$data['course']->id)->orderBy('year','desc')
                                                             ->get()->groupBy('type')->toArray() ;
                                                         $data['course_rate']=$this->CalculateCourseRate($data['course']->id);
                                                         $data['lesson_rate']=$this->CalculateLessonRate($lesson->id);
                                                         $data['rates']=$lesson->rates()->with('user')->where('status','=','approved')->orderBy('created_at','desc')->limit(4)->get();
                                                         if (auth('web')->check()) {
                                                             $data['user_rate']=RateLesson::where('lesson_id','=',$lesson->id)->where('user_id',auth('web')->user()->id)->first();
                                                         } else{
                                                             $data['user_rate']=null;
                                                         }

                                                         $data['lesson']=$lesson;

                                                         return view('frontend.lesson.video',$data);
                                                     } else{

//                                                         return 'not-allowed-to-watch-this-lesson';
                                                         $data=$this->cache();
                                                         if ($lesson->type=='youtube' or $lesson->type=='cloud' or  $lesson->type=='drive'){
                                                             $data['video']="#";
                                                         }
                                                         $data['course']=$course;
                                                         $data['exams']=CourseFile::where('course_id','=',$data['course']->id)->orderBy('year','desc')
                                                             ->get()->groupBy('type')->toArray() ;
                                                         $data['course_rate']=$this->CalculateCourseRate($data['course']->id);
                                                         $data['lesson_rate']=$this->CalculateLessonRate($lesson->id);
                                                         $data['rates']=$lesson->rates()->with('user')->where('status','=','approved')->orderBy('created_at','desc')->limit(4)->get();
                                                         $data['user_rate']=null;
                                                         $data['lesson']=$lesson;

                                                         return view('frontend.lesson.video',$data);
                                                     }
                                        } else{
//                                           return 'not-allowed-to-watch-this-lesson';
                                            $data=$this->cache();
                                            if ($lesson->type=='youtube' or $lesson->type=='cloud' or  $lesson->type=='drive'){
                                                $data['video']="#";
                                            }
                                            $data['course']=$course;
                                            $data['exams']=CourseFile::where('course_id','=',$data['course']->id)->orderBy('year','desc')
                                                ->get()->groupBy('type')->toArray() ;
                                            $data['course_rate']=$this->CalculateCourseRate($data['course']->id);
                                            $data['lesson_rate']=$this->CalculateLessonRate($lesson->id);
                                            $data['rates']=$lesson->rates()->with('user')->where('status','=','approved')->orderBy('created_at','desc')->limit(4)->get();
                                            $data['user_rate']=null;
                                            $data['lesson']=$lesson;

                                            return view('frontend.lesson.video',$data);
                                        }
                                    }
                             }
                } else{
                    return redirect()->route('course.details',$course->slug);
                }
            } else{
                return redirect()->route('course.details',$course->slug);
            }
        }else{
            return redirect()->route('courses');
        }
    }

    private function CalculateLessonRate($lesson_id){
        $rates=RateLesson::where('lesson_id','=',$lesson_id)->get()->groupBy('rate')->toArray() ;
        if (count($rates)>0){
            $RATES=[];
            $total=0;
            $RATE=0;
            foreach ($rates as  $key => $_rates){
                $RATES[$key]=count($_rates);
                $total+=count($_rates);
            }
            foreach ($RATES as $key=>$value){
                $RATE+=$key*$value;
            }
            return array($RATE/$total,$total);

        }
        return array(0,0);
    }

    private function CalculateCourseRate($course_id){
        $rates=RateCourse::where('course_id','=',$course_id)->get()->groupBy('rate')->toArray() ;
        if (count($rates)>0){
            $RATES=[];
            $total=0;
            $RATE=0;
            foreach ($rates as  $key => $_rates){
                $RATES[$key]=count($_rates);
                $total+=count($_rates);
            }
            foreach ($RATES as $key=>$value){
                $RATE+=$key*$value;
            }
            return array($RATE/$total,$total);

        }
        return array(0,0);
    }

    private function courseIsFree($course){
        return  $course->isFree();
     }

    private function getResponse($course_id,$video){
        $filePath='courses_files'.DS.$course_id.DS.$video;
        if (file_exists(storage_path('app'.DS.$filePath))){
            $file=Storage::disk('local')->get($filePath);
            $type=Storage::disk('local')->mimeType($filePath);
            $response = response()->make($file, 200);
            $response->header("Content-Type",$type);
            return $response;
        } else{
            return abort(404);
        }
    }

    public function checkAuthorization($course_id,$lesson_id){
              $course=Course::find($course_id);
              if ($course) {
                  $lesson=Lesson::find($lesson_id);
                  if ($this->courseIsFree($course)){
                        if ($lesson){
                            return $this->getResponse($course->id,$lesson->video);
                        } else{
                            return '#';
                        }
                  }else{
                              //course is paid
                      if ($lesson->status){
                          return $this->getResponse($course->id,$lesson->video);
                      }else{
                          if (auth('web')->check()){
                              if (checkIfUserHasThisCourse($course)=='opened'){
                                  return $this->getResponse($course->id,$lesson->video);
                              } else{
                                  return  '#';
                              }
                          } else{
                              return  '#';
                          }
                      }
                  }
              }else{
                     return  '#';
              }
       }

    public function loadMoreLessonRates(Request $request){
        //num of rows to skip it and take the limit and next of it
        $final=false;
        if ($request->ajax()){
            $rates=DB::table('rate_lessons')->select(['users.name','rate_lessons.*'])
                ->where('lesson_id','=',$request->lesson_id)
                ->where('status','=','approved')
                ->join('users', function($join)
                {
                    $join->on('rate_lessons.user_id', '=', 'users.id');
                })->orderBy('rate_lessons.created_at','desc')->skip($request->skip)->take(4)->get();
            if (count($rates)<4){
                $final=true;
            }
            return response()->json(['rates'=>$rates,'final'=>$final],200);
        }
    }


}
