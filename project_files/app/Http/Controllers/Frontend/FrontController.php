<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
use App\course;
use App\CourseFile;
use App\Department;
use App\Http\Controllers\Controller;
use App\LessonFile;
use App\Post;
use App\Project;
use App\RateCourse;
use App\RateLesson;
use App\University;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class FrontController extends Controller
{
    public function __construct() {

        $this->middleware(['auth:web','blocked'])->only('buyCourse');
        $this->middleware(['blocked','auth:web'])->only('profile');
        $this->middleware(['auth:web','blocked'])->only('updateProfile');
        $this->middleware(['auth:web','blocked'])->only('resetPassword');
        $this->middleware(['auth:web','blocked'])->only('CancelOrder');
        $this->middleware(['auth:web','blocked'])->only('downloadExam');
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
        $recent_posts=Cache::rememberForever('recent_posts',function (){
            return Post::with('category')->where('status','=',1)->where('status','=',1)
                ->orderBy('created_at','desc')->limit(related)->get();
        });
        $data['recent_posts']=$recent_posts;
        $feedback=Cache::rememberForever('feedbackImages_Videos',function (){
            return DB::table('feedback')->select(
                ['feedback.*','countries.Name as country','departments.name as department','universities.name as university'])
                ->join('countries','feedback.country_id','=','countries.id')
                ->join('departments','feedback.department_id','=','departments.id')
                ->join('universities','departments.university_id','=','universities.id')
                ->whereIn('type',['video','image','audio'])
                ->orderBy('created_at','desc')->limit(6)->get();
        });
        $data['feedbackImages_Videos']=$feedback;
        return $data;
    }

    public function index()
    {
        $data=$this->cache();
        $recent_courses=Cache::rememberForever('recent_courses',function () {
            return  course::with(['users','department','department.university','instructor'])
                ->orderBy('created_at','desc')
                ->limit(3)->get();
        });
        $data['recent_courses']=$recent_courses;
        $projects=Cache::rememberForever('projects',function () {
            return  Project::where('status','=',1)->orderBy('created_at','desc')
                ->limit(6)->get();
        });
        $data['projects']=$projects;

        return view('frontend.index',$data);
    }

    public function buyCourse(Request $request){

        $request->validate([
            'course_id'=>['required','exists:courses,id',
                Rule::unique('course_user','course_id')
                    ->where('user_id',auth('web')->user()->id)],
        ]);

        $course=course::find($request->course_id);
        if ($course){
            Cache::forget('orders');
            removeCache('order');
            $paid=DB::table('course_user')->insert([
                'course_id'=>$request->course_id,
                'user_id'=>auth('web')->user()->id,
                'status'=>'closed',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]) ;


            return redirect()->route('course.lessons',$course->slug);

        }  else{
            return redirect()->route('courses');
        }

    }

    public function uploadedFiles($model,$name,$id=null,$type=null){
        if ($id!=null){
            if ($type!=null){
                $DIR=$model.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR;
            }else{
                $DIR=$model.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR;
            }
        }else{
            $DIR=$model.DIRECTORY_SEPARATOR;
        }

        $filePath=$DIR.$name;

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

    public function downloadExam($course_id,$exam_id){

        $course=course::find($course_id);
        if ($course){

                $exam=CourseFile::find($exam_id);
                if ($exam){

                    $newName=ucfirst($course->code).'_'.ucfirst($exam->type).'_'.ucfirst($exam->term).'_'.$exam->year;
                    if ($exam->status=='opened'){

                              if ($exam->hosting=='cloud'){
                                        $this->downloadFromCloud($exam->path,$newName);
                              }else{
                                  $filePath='courses_files'.DS.$course->id.DS.'exams'.DS.$exam->path;
                                  if (file_exists(storage_path('app'.DS.$filePath))){
                                      $type=Storage::disk('local')->mimeType('courses_files'.DS.$course_id.DS.'exams'.DS.$exam->path);
                                      $extension=explode('.',$exam->path);
                                      $extension=end($extension);
                                      $newName=$newName.'.'.$extension;
                                      return response()->download(storage_path('app'.DS.$filePath), $newName,['contentType',$type]);
                                  } else{
                                      return abort(404);
                                  }
                              }
                    }else{

                               if (in_array(auth('web')->user()->id,(array)json_decode($exam->shared))){

                                   if ($exam->hosting=='cloud'){
                                       $this->downloadFromCloud($exam->path,$newName);
                                   }else{
                                       $filePath='courses_files'.DS.$course->id.DS.'exams'.DS.$exam->path;
                                       if (file_exists(storage_path('app'.DS.$filePath))){
                                           $type=Storage::disk('local')->mimeType('courses_files'.DS.$course_id.DS.'exams'.DS.$exam->path);
                                           $extension=explode('.',$exam->path);
                                           $extension=end($extension);
                                           $newName=$newName.'.'.$extension;
                                           return response()->download(storage_path('app'.DS.$filePath), $newName,['contentType',$type]);
                                       } else{
                                           return abort(404);
                                       }
                                   }
                               }else{
                                   return redirect()->route('course.details',$course->slug);
//                                   abort(404);    //return 'fail or null or empty'
                               }
                    }

                }else{
                    abort(404);
                }

        }else{
            abort(404);
        }


    }

    public function downloadLessonFiles($course_id,$file_id){


        $course=course::find($course_id);
        if ($course){
            $file=LessonFile::find($file_id);
            if ($course->isFree()){
                if ($file){
                    if($file->hosting=='cloud'){
                                  $this->downloadFromCloud($file->path,$file->name);
                    }elseif ($file->hosting=='drive'){
                        header('location:'.$file->path);
                    }else{
                        $filePath='courses_files'.DS.$course->id.DS.'files'.DS.$file->path;
                        if (file_exists(storage_path('app'.DS.$filePath))){
                            $type=Storage::disk('local')->mimeType('courses_files'.DS.$course_id.DS.'files'.DS.$file->path);
                            $extension=explode('.',$file->path);
                            $extension=end($extension);
                            $new_file_name=$file->name.'.'.$extension;
                            return response()->download(storage_path('app'.DS.$filePath), $new_file_name,['contentType',$type]);
                        } else{
//                       return abort(404);
                            return '#';
                        }
                    }

                }else{
//                   abort(404);
                    return '#';
                }
            }else{
                if ($file){
                    if ($file->isFree) {
                        if($file->hosting=='cloud'){
                            $this->downloadFromCloud($file->path,$file->name);
                        }elseif ($file->hosting=='drive'){
                            header('location:'.$file->path);
                        } else{
                            $filePath='courses_files'.DS.$course->id.DS.'files'.DS.$file->path;
                            if (file_exists(storage_path('app'.DS.$filePath))){
                                $type=Storage::disk('local')->mimeType('courses_files'.DS.$course_id.DS.'files'.DS.$file->path);
                                $extension=explode('.',$file->path);
                                $extension=end($extension);
                                $new_file_name=$file->name.'.'.$extension;
                                return response()->download(storage_path('app'.DS.$filePath), $new_file_name,['contentType',$type]);
                            } else{
//                       return abort(404);
                                return '#';
                            }
                        }
                    } else{
                        if (auth('web')->check()){
                            if (checkIfUserHasThisCourse($course)=='opened'){
                                if($file->hosting=='cloud'){
                                    $this->downloadFromCloud($file->path,$file->name);
                                } elseif ($file->hosting=='drive'){

                                    return \Redirect::intended($file->path);

                                }else{
                                    $filePath='courses_files'.DS.$course->id.DS.'files'.DS.$file->path;
                                    if (file_exists(storage_path('app'.DS.$filePath))){
                                        $type=Storage::disk('local')->mimeType('courses_files'.DS.$course_id.DS.'files'.DS.$file->path);
                                        $extension=explode('.',$file->path);
                                        $extension=end($extension);
                                        $new_file_name=$file->name.'.'.$extension;

                                        return response()->download(storage_path('app'.DS.$filePath), $new_file_name,['contentType',$type]);
                                    } else{
//                       return abort(404);
                                        return '#';
                                    }
                                }
                            } else{
                                return '#';
                            }
                        } else{
                            return '#';
                        }
                    }
                } else{
//                    abort(404);
                    return '#';
                }
            }

        }else{
//            abort(404);
            return '#';
        }


    }

    private function downloadFromCloud($file_path,$new_file_name){
        $extension=explode('.',$file_path);
        $extension=end($extension);
        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=".$new_file_name.'.'.$extension);
        echo readfile($file_path);
    }

    public function getDepartmentsInProfile(Request $request){

        if ($request->ajax()){
            $departments=Department::where('university_id','=',$request->university_id)->get();

            return response()->json(['departments'=>$departments],200);
        }  else{
            return response()->json([],200);
        }
    }

    public function profile(){
        $data=$this->cache();
        $total=RateCourse::where('user_id','=',auth('web')->user()->id)->count();
        $total+=RateLesson::where('user_id','=',auth('web')->user()->id)->count();
        $data['reviews']=$total;

        $total=DB::table('course_user')->where('user_id','=',auth('web')->user()->id)
            ->where('status','=','opened')->count();
        $data['my_courses']=$total;

        $data['countries']= Cache::rememberForever('countries',function (){
            return    DB::table('countries')->select(['id','Name'])->get();
        });

        $data['orders']=DB::table('course_user')->select(['course_user.*','courses.name as course'])
            ->join('courses','course_user.course_id','=','courses.id')
            ->where('user_id','=',auth('web')->user()->id)
            ->where('status','=','closed')->get();

        return view('frontend.auth.account',$data);
    }

    public function updateProfile(Request $request){
        $user=auth('web')->user();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['required', 'numeric', 'unique:users,phone,'.$user->id],
            'country' => ['required', 'string','max:300'],
            'address' => ['required', 'string','max:255'],
            'department_id' => ['required', 'numeric', 'exists:departments,id'],

        ]);
        $validated_data=$request->except(['_token','_method','photo']);
        if ($request->hasFile('photo')){
            if ($user->photo=='default.png'){
                $request->file('photo')->storeAs('users',$request->file('photo')->hashName());
            }else{
                Storage::disk('local')->delete('users'.DS.$user->photo);
                $request->file('photo')->storeAs('users',$request->file('photo')->hashName());
            }
            $validated_data['photo']=$request->file('photo')->hashName();
        }else{
            $validated_data['photo']=$user->photo;
        }

        if ($user->update($validated_data)){
            auth('web')->loginUsingId($user->id);
            return redirect()->route('user.profile')->with('result','Yoy Account Updated Successfully');
        }


    }

    public function resetPassword(Request $request){
        $request->validate([
              'old_password' => ['required', 'string'],
              'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user=User::find(\auth('web')->user()->id);
        if (Hash::check($request->old_password,$user->password)){
           $r=DB::table('users')->where('email','=',$user->email)
               ->update(['password'=>Hash::make($request->password)]);
            auth('web')->loginUsingId($user->id);
            return redirect()->route('user.profile')->with('result','Password Resets Successfully');
        }else{
            return redirect()->route('user.profile')->withErrors('Incorrect Current Password');
        }
    }

    public function CancelOrder ($orderId){
          $order=DB::table('course_user')->where('user_id','=',auth('web')->user()->id)
              ->where('status','=','closed')
              ->where('id','=',$orderId)->delete();
          return redirect()->back();
    }








    /*
public function enrollFirst(Request $request){


             $request->validate([
                 'course_id'=>['required','exists:courses,id',
                     Rule::unique('course_user','course_id')
                         ->where('user_id',auth('web')->user()->id)],
             ]);

                $course=course::where('id','=',$request->course_id)->first();

                if ($course){
                    $course->users()->attach([auth('web')->user()->id]);
                    return redirect()->route('course.details',$course->slug);
                } else{
                    return redirect()->back();
                }

}
     */

}
