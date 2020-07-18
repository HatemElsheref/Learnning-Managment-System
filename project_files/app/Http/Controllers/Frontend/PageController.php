<?php

namespace App\Http\Controllers\Frontend;

use App\Category;
use App\Http\Controllers\Controller;
use App\Instructor;
use App\University;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
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

    public function about(){
        $data=$this->cache();
        $data['instructors']=Instructor::all();
                 return view('frontend.about',$data);
    }
    public function contact(){
        $data=$this->cache();
        return view('frontend.contact',$data);
    }
    public function services(){
        $data=$this->cache();
        return view('frontend.services',$data);
    }
}
