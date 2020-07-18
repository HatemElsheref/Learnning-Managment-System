<?php

namespace App\Http\Controllers\Frontend;

use App\course;
use App\Department;
use App\Http\Controllers\Controller;
use App\RateCourse;
use Illuminate\http\Request;
use Illuminate\Support\Facades\Cache;
use App\Category;
use App\University;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{

    private function cache(){
        $data=[];
        $data['categories']=Cache::rememberForever('categories',function (){
            return   Category::with('posts')->get();
        });

        $data['universities']=Cache::rememberForever('universities',function (){
            return   University::with('departments','departments.courses')->get();
        });

        $data['total']=Cache::rememberForever('total_courses',function (){
            return   Course::all()->count();
        });

        return $data;
    }

    public function index(){
        $data=$this->cache();
        $departments=Cache::rememberForever('departments',function (){
            return   Department::with('courses')->get();
        });
        $data['departments']=$departments;
        $data['courses']=course::with(['users','department','department.university','instructor'])->orderBy('created_at','desc')->paginate(pagination);


        return view('frontend.course.index',$data);
    }

    public function filter(Request $request){


        $data=$this->cache();
        $data['departments']=Cache::rememberForever('departments',function (){
            return   Department::with('courses')->get();
        });
                  $departments=[];
                  if ($request->universities){
                      foreach ($request->universities as $university){
                          $university=University::with('departments')->where('id',$university)->first();
                         $departments+=$university->departments->pluck('id')->toArray();
                      }
                  }

                  if ($request->departments){
                    $departments+=$request->departments;
                  }
                  if ($departments){
                      $data['courses']=course::with(['users','rates','department','department.university','instructor'])
                          ->whereIn('department_id', $departments)
                          ->orderBy('created_at','desc')->paginate(pagination);
              }  else{
                      $data['courses']=course::with(['users','rates','department','department.university','instructor'])
                          ->whereIn('department_id', $departments)
                          ->orderBy('created_at','desc')->paginate(pagination);
                  }
//        return view('frontend.course.index',$data);
        return view('frontend.course.course_search',$data);

    }

    public function search(Request $request){

        if ($request->has('university')){
            if($request->university=='all'){
                $data=$this->find($request);
            }else{
                   $university=University::with('departments')->where('slug','=',$request->university)->first();

                   $deps=$university->departments->pluck('id')->toArray();
                $word='%'.$request->q.'%';
                $data=$this->cache();
                $departments=Cache::rememberForever('departments',function (){
                    return   Department::with('courses')->get();
                });
                $data['departments']=$departments;
                $data['courses']=course::with(['users','department','department.university','instructor'])
                    ->whereIn('department_id',$deps)
                    ->where('name','like',$word)
                    ->orWhere('description','like',$word)
                    ->orderBy('created_at','desc')
                    ->paginate(pagination);
            }
        }else{
                $data=$this->find($request);
        }

//        return view('frontend.course.index',$data);
        return view('frontend.course.course_search',$data);

    }

    public function course($slug){
        $data=$this->cache();
        $course=course::with('instructor','users','department','department.university','parts','parts.lessons','parts.lessons.files','files','articles')
            ->where('slug','=',$slug)->first();
        if ($course){
            if (auth('web')->check()){
                if (in_array(auth('web')->user()->id,$course->users->pluck('id')->toArray())) {
                    $data['user_has_this_course']=true;
                } else{
                    $data['user_has_this_course']=false;
                }
            }

            $data['course_rate']=$this->CalculateCourseRate($course->id);
            if (auth('web')->check()) {
                $data['user_rate']=RateCourse::where('course_id','=',$course->id)->where('user_id',auth('web')->user()->id)->first();
            } else{
                $data['user_rate']=null;
            }
            $rates=$course->rates()->with('user')->where('status','=','approved')->orderBy('created_at','desc')->limit(4)->get();
            $data['rates']=$rates;
            $data['course']=$course;
            return view('frontend.course.course_details',$data);
        }   else{
                return view('frontend.404',$data);
        }

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

    public function loadMoreCourseRates(Request $request){
        //num of rows to skip it and take the limit and next of it
        $final=false;
        if ($request->ajax()){
            $rates=DB::table('rate_courses')->select(['users.name','rate_courses.*'])
            ->where('course_id','=',$request->course_id)
                ->where('status','=','approved')
                ->join('users', function($join)
                {
                    $join->on('rate_courses.user_id', '=', 'users.id');
                })->orderBy('rate_courses.created_at','desc')->skip($request->skip)->take(4)->get();
                           if (count($rates)<4){
                               $final=true;
                           }
            return response()->json(['rates'=>$rates,'final'=>$final],200);
        }
    }

    private  function find($request){
        $word='%'.$request->q.'%';
        $data=$this->cache();
        $departments=Cache::rememberForever('departments',function (){
            return   Department::with('courses')->get();
        });
        $data['departments']=$departments;
        $data['courses']=course::with(['users','department','department.university','instructor'])
            ->orderBy('created_at','desc')
            ->where('name','like',$word)
            ->orWhere('description','like',$word)
            ->paginate(pagination);
        return $data;
    }
}
