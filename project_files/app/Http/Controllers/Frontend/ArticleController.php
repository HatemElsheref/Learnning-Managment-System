<?php

namespace App\Http\Controllers\Frontend;

use App\Article;
use App\course;
use App\Department;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Category;
use App\University;

class ArticleController  extends Controller
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
       $course=course::where('slug','=',$slug)->first();                                         //get all articles in this course
       if ($course){
           $data['articles']=Article::with(['course','instructor'])
               ->where('course_id','=',$course->id)
               ->latest()->orderBy('created_at','desc')
               ->paginate(pagination);
           $recent_course_articles=Article::with('course')               // get latest articles in this course  to side bar
               ->where('course_id','=',$course->id)
               ->where('id','!=',$course->id)
               ->orderBy('created_at','desc')
               ->limit(5)->get();

           $data['recent_course_articles']=$recent_course_articles;
           return view('frontend.articles.index',$data);
       }   else{
           return view('frontend.404',$data );
       }


   }

    public function article($slug){

        $article=Article::with(['instructor','course','course.department','course.department.courses'])   //show the article
            ->where('slug','=',$slug)->first();
       if ($article){
           $data=$this->cache();
           $data['articles']=Article::where('course_id','=',$article->course_id)                         //articles in the same course
               ->where('id','!=',$article->id)
               ->orderBy('created_at','desc')
               ->limit(5)->get();
           $courses_ids=$article->course->department->courses->pluck('id')->toArray();
           $data['random']=Article::with(['instructor','course'])->whereIn('course_id',$courses_ids)->inRandomOrder()->limit(5)->get();              //articles in the same department
           $data['article']=$article;

           return view('frontend.articles.article',$data);
       }
       return view('frontend.404');
   }

    public function department($slug){
        $department=Department::with('courses')->where('slug','=',$slug)->first();
        if ($department) {
            $data=$this->cache();
            $courses=$department->courses->pluck('id')->toArray();
            $data['articles']=Article::with(['instructor','course'])   //show the article in this department
            ->whereIn('course_id',$courses)->latest()->orderBy('created_at','desc')->paginate(pagination);
            $data['recent_course_articles']=Article::whereIn('course_id',$courses)                         //articles in the same course
                ->orderBy('created_at','desc')
                ->limit(5)->get();
            return view('frontend.articles.index',$data);
        }else {
            return view('frontend.404');
        }
    }

    public function search(Request $request){
        $data=$this->cache();
        $data['articles']=Article::with(['instructor','course'])
            ->where('title','like','%'.$request->q.'%')
            ->orWhere('subtitle','like','%'.$request->q.'%')
            ->orWhere('content','like','%'.$request->q.'%')->paginate(pagination);
        $recent_course_articles=Article::with('course')               // get latest articles in this course  to side bar
        ->orderBy('created_at','desc')
            ->limit(5)->get();
        $data['recent_course_articles']=$recent_course_articles;

        return view('frontend.articles.index',$data);
    }

    public function searchByDate($date){
        $data=$this->cache();
        $dateNew=Carbon::createFromFormat('d-m-Y',$date);
        $data['articles']=Article::with(['instructor','course'])
            ->whereYear('created_at','=',$dateNew)->paginate(pagination);
        $recent_course_articles=Article::with('course')               // get latest articles in this course  to side bar
            ->orderBy('created_at','desc')
            ->limit(5)->get();
        $data['recent_course_articles']=$recent_course_articles;
        return view('frontend.articles.index',$data);
    }

}
