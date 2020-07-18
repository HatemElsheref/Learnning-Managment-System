<?php

namespace App\Http\Controllers\Frontend;

use App\Article;
use App\Http\Controllers\Controller;
use App\Department;
use App\course;
use App\Category;
use App\University;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class UniversityController extends Controller
{
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

   public function index($slug){

       $data=$this->cache();
       $with=[
           'departments','departments.courses','departments.courses.files','departments.courses.parts',
           'departments.courses.articles','departments.courses.parts.lessons','departments.courses.parts.lessons.files'
       ];
       $data['university']=University::with($with)->where('Slug','=',$slug)->first();
       $courses=0;
       $articles=0;
       $exams=0;
       $files=0;
       $COURSES=[];
       $DEPARTMENTS=[];

       foreach ($data['university']->departments as $department) {
                 foreach ($department->courses as $course){
                    $courses++;
                    array_push($COURSES,$course->id);
                    array_push($DEPARTMENTS,$department->id);
                    $exams+=count($course->files);
                    $articles+=count($course->articles);
                    foreach ($course->parts as $part){
                        foreach ($part->lessons  as $lesson) {
                            $files+=count($lesson->files);
                        }
                    }
                 }
       }
       $data['courses']=$courses;
       $data['articles']=$articles;
       $data['exams']=$exams;
       $data['files']=$files;
       $Articles=Article::with('course','instructor')->whereIn('course_id',$COURSES)->latest()->limit(5)->get();
       $Courses=Course::with('users','rates','department','department.university','instructor')->whereIn('department_id',$DEPARTMENTS)->latest()->limit(3)->get();
       $data['Articles']=$Articles;
       $data['Courses']=$Courses;
       return view('frontend.university.university',$data);
   }

    public function university($slug){

        $data=$this->cache();
        $with=[
            'university','courses','courses.files','courses.parts',
            'courses.articles','courses.parts.lessons','courses.parts.lessons.files'
        ];
        $dep=Department::with($with)->where('Slug','=',$slug)->first();
        if ($dep){
            $data['department']=$dep;
            $courses=0;
            $articles=0;
            $exams=0;
            $files=0;
            $COURSES=[];

            foreach ($data['department']->courses as $course){
                $courses++;
                array_push($COURSES,$course->id);
                $exams+=count($course->files);
                $articles+=count($course->articles);
                foreach ($course->parts as $part){
                    foreach ($part->lessons  as $lesson) {
                        $files+=count($lesson->files);
                    }
                }
            }

            $data['courses']=$courses;
            $data['articles']=$articles;
            $data['exams']=$exams;
            $data['files']=$files;

            $Articles=Article::with('course','instructor')->whereIn('course_id',$COURSES)->latest()->limit(5)->get();
            $Courses=Course::with('rates','users','department','department.university','instructor')->where('department_id','=',$dep->id)->latest()->limit(3)->get();
            $data['Articles']=$Articles;
            $data['Courses']=$Courses;
            return view('frontend.university.department',$data);
        }   else{

            return view('frontend.404');
        }


   }

}
