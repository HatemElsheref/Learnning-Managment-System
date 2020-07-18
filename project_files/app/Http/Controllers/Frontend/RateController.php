<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Lesson;
use App\RateCourse;
use App\Course;
use App\RateLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class RateController extends Controller
{
    public function __construct() {
        $this->middleware('auth:web');
    }


    public function addCourseRate(Request $request){


                 $request->validate([
//                     'course_id'=>['required','exists:courses,id',
                     'course_id'=>['required','numeric',
                         Rule::unique('rate_courses','course_id')
                             ->where('user_id',auth('web')->user()->id)],
                     'title'=>'required|max:191',
                     'content'=>'required|max:191',
                     'rate'=>'required|in:1,2,3,4,5'
                 ],[
                     'rate.required'=>'You Must Rate The Coures',
                     'rate.in'=>'Rate Must Be 1 , 2 , 3 , 4 , 5'
                 ]);
                 $course=Course::find($request->course_id);
                 if ($course){
                     if (in_array(auth('web')->user()->id,$course->users->pluck('id')->toArray()) or $course->isFree()) {
                         $validated_data=$request->all();
                         $validated_data['status']='approved';
                         $validated_data['user_id']=auth('web')->user()->id;
                         $rate=RateCourse::create($validated_data);
                         if ($rate){
                             removeCache('course');
                             return redirect()->route('course.details',$rate->course->slug);
                         } else{
                             return redirect()->route('course.details',$course->slug) ;
                         }
                     } else{
                         return redirect()->route('course.details',$course->slug) ;
                     }
                 } else{
                     return redirect()->route('courses') ;
                 }

    }

    public function updateCourseRate(Request $request,$id){

                 $request->validate([
//                     'course_id'=>['required','exists:courses,id',
                     'course_id'=>['required','numeric',
                         Rule::unique('rate_courses','course_id')
                             ->where('user_id',auth('web')->user()->id)->ignore($id)],
                     'title'=>'required|max:191',
                     'content'=>'required|max:191',
                     'rate'=>'required|in:1,2,3,4,5'
                 ],[
                     'rate.required'=>'You Must Rate The Coures',
                     'rate.in'=>'Rate Must Be 1 , 2 , 3 , 4 , 5'
                 ]);

        $rate=RateCourse::find($id);

        if ($rate){
            $validated_data=$request->all();
            $validated_data['status']='approved';
            $validated_data['user_id']=auth('web')->user()->id;
            if ($rate->update($validated_data)){
                removeCache('course');
                return redirect()->route('course.details',$rate->course->slug);
            }
        }else{
              return redirect()->route('index');
        }
    }

    public function addLessonRate(Request $request){

        $request->validate([
//                     'course_id'=>['required','exists:courses,id',
            'lesson_id'=>['required','numeric',
                Rule::unique('rate_lessons','lesson_id')->where('user_id',auth('web')->user()->id)],

//            'lesson_id'=>'required|numeric',
            'title'=>'required|max:191',
            'content'=>'required|max:191',
            'rate'=>'required|in:1,2,3,4,5'
        ],[
            'rate.required'=>'You Must Rate The Lesson',
            'rate.in'=>'Rate Must Be 1 , 2 , 3 , 4 , 5'
        ]);

//        $q=RateLesson::where('lesson_id','=',$request->lesson_id)->where('user_id','=',auth('web')->user()->id)->get();
//        if ($q){
//            dd($q);
//            return redirect()->back()->withErrors('You Already Rated This Lesson');
//        }
        $lesson=Lesson::find($request->lesson_id);
        if ($lesson){
                         $course=$lesson->part->course;
                if ((checkIfUserHasThisCourse($course)=='opened') or ($lesson->status==true) or ($course->isFree())){
                $validated_data=$request->all();
                $validated_data['status']='approved';
                $validated_data['user_id']=auth('web')->user()->id;
                $rate=RateLesson::create($validated_data);
                if ($rate){
                    removeCache('course');
                    return redirect()->route('course.video',[$course->id,$lesson->id]);
                } else{
                    return redirect()->route('course.video',[$course->id,$lesson->id]);
                }
            } else{
                return redirect()->route('course.lessons',$course->slug) ;
            }
        } else{
            return redirect()->back();
        }

    }

    public function updateLessonRate(Request $request,$id){

        $request->validate([
//                     'course_id'=>['required','exists:courses,id',
            'lesson_id'=>['required','numeric',
                Rule::unique('rate_lessons','lesson_id')
                    ->where('user_id',auth('web')->user()->id)->ignore($id)],
            'title'=>'required|max:191',
            'content'=>'required|max:191',
            'rate'=>'required|in:1,2,3,4,5'
        ],[
            'rate.required'=>'You Must Rate The Coures',
            'rate.in'=>'Rate Must Be 1 , 2 , 3 , 4 , 5'
        ]);

        $rate=RateLesson::find($id);

        if ($rate){
            $validated_data=$request->all();
            $validated_data['status']='approved';
            $validated_data['user_id']=auth('web')->user()->id;
            if ($rate->update($validated_data)){
                removeCache('course');
                return redirect()->back();
            }
        }else{
            return redirect()->route('index');
        }
    }


}
