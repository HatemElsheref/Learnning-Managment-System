<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Category;
use App\Post;
use App\Tag;
use App\University;

class BlogController extends Controller
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
        $tags=Cache::rememberForever('tags',function (){
            return   Tag::all();
        });
        $data['tags']=$tags;
        $recent_posts=Cache::rememberForever('recent_posts',function (){
            return Post::with('category')->where('status','=',1)->where('status','=',1)->orderBy('created_at','desc')->limit(related)->get();
        });
        $data['recent_posts']=$recent_posts;
        return $data;
    }

   public function index(){
       $data=$this->cache();
       $data['posts']=Post::with(['category','instructor'])
           ->where('status','=',1)
           ->latest()->orderBy('created_at','desc')
           ->paginate(pagination);
       return view('frontend.blog.index',$data);
   }

    public function post($slug){

        $post=Post::with(['instructor','category','tags'])
            ->where('slug','=',$slug)
            ->where('status','=',1)
            ->first();
       if ($post){
           $data=$this->cache();
           $data['posts']=Post::where('category_id','=',$post->category_id)
               ->where('status','=',1)
               ->where('id','!=',$post->id)
               ->orderBy('created_at','desc')
               ->limit(related)->get();
           $data['random']=Post::with(['instructor','category'])->inRandomOrder()
               ->where('status','=',1)->limit(5)->get();
           $data['post']=$post;
           return view('frontend.blog.post',$data);
       }
       return view('frontend.404');
   }

   public function searchByCategory($slug){  //slug -> category name
        $category=Category::where('name',$slug)->first();
        if ($category){
            $posts=Post::with(['instructor','category'])
                ->where('category_id','=',$category->id)
                ->where('status','=',1)
                ->paginate(pagination);
            $data=$this->cache();
            $data['posts']=$posts;
            return view('frontend.blog.index',$data);
        }   else{
              return view('frontend.404');
        }
   }

    public function searchByTag($slug){ //slug->tag name
        $tag=Tag::where('name',$slug)->first();
        if ($tag){
            $posts=$tag->posts()->with(['instructor','category'])->where('status','=',1)->paginate(pagination);
            $data=$this->cache();
            $data['posts']=$posts;
            return view('frontend.blog.index',$data);
        }   else{
            return view('frontend.404');
        }
    }

    public function search(Request $request){
        $posts=Post::with(['instructor','category'])
            ->where('status','=',1)
            ->where('title','like','%'.$request->q.'%')
            ->orWhere('description','like','%'.$request->q.'%')
            ->orWhere('content','like','%'.$request->q.'%')->paginate(pagination);

        $data=$this->cache();
        $data['posts']=$posts;
        return view('frontend.blog.index',$data);
    }

    public function searchByDate($date){
        $dateNew=Carbon::createFromFormat('d-m-Y',$date);
        $posts=Post::with(['instructor','category'])
            ->where('status','=',1)
            ->whereYear('created_at','=',$dateNew)->paginate(pagination);
        $data=$this->cache();
        $data['posts']=$posts;
        return view('frontend.blog.index',$data);
    }

}
