<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Project;
use App\Category;
use App\University;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
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

   public function index(){

       $data=$this->cache();
       $data['projects']=Project::where('status','=',1)->latest()->paginate(pagination);
       return view('frontend.project.index',$data);
   }

    public function project($id){

        $data=$this->cache();
        $project=Project::where('status','=',1)->where('id','=',$id)->first();
       if ($project){
           $photos=DB::table('uploads')->select('*')
               ->where('parent','=','project')
               ->where('parent_id','=',$project->id)
               ->where('status','=',1)
               ->where('mimes','=','image')->get();
           $data['project_photos']=$photos;
           $data['project']=$project;
           return view('frontend.project.project_details',$data);
       }
       return view('frontend.404');
   }

}
