<?php

namespace App\Http\Controllers\Dashboard;

use App\Tag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TagController extends Controller
{
    public function __construct() {

        $this->middleware('DashboardPermission:read_tag')->only('index');
        $this->middleware('DashboardPermission:create_tag')->only(['create','store']);
        $this->middleware('DashboardPermission:update_tag')->only(['update','edit']);
        $this->middleware('DashboardPermission:delete_tag')->only(['destroy','MultiDelete']);
    }

    public function index(){
        $tags=Tag::OrderBy('created_at','desc')->get();
        return view('dashboard.blog.tags.index',['tags'=>$tags]);
    }
    public function create(){
        return view('dashboard.blog.tags.create');
    }
    public function store(Request $request){
        $request->validate([
            'name'=>'required|unique:tags,name'
        ]);
        $validated_data['name']=$request->name;
        $tag=Tag::create($validated_data);
        Cache::forget('tags');
        removeCache('tag');
        removeCache('post');
        removeCache('category');
        if ($tag){
            toast('Tag Created Successfully','success',AlertPosition());
            return redirect(route('tag.index'));
        }
        else{
            toast('Failed To Create Tag','error',AlertPosition());
            return redirect(route('tag.create'));
        }
    }
    public function edit(Tag $tag){
        if ($tag) {
            return view('dashboard.blog.tags.edit', compact('tag'));
        }else{
            toast('Tag Not Found ','error',AlertPosition());
            return redirect()->route('tag.index');
        }
    }
    public function update(Request $request,Tag $tag){
        if (!$tag){
            toast('Tag Not Found','error',AlertPosition());
            return redirect()->route('tag.index');
        }
        $request->validate([
            'name'=>'required|string|max:191|unique:tags,name,'.$tag->id
        ]);
        $validated_data['name']=$request->name;
        $tag->update($validated_data);
        Cache::forget('tags');
        removeCache('tag');
        removeCache('post');
        removeCache('category');
        toast('Tag Updated Successfully','success',AlertPosition());
        return redirect(route('tag.index'));
    }
    public function destroy(Tag $tag){
        //remove tag will remove all attached posts and photos inside it
        if (!$tag) {
            toast('Tag Not Found','error',AlertPosition());
            return redirect()->route('tag.index');
        }
        foreach ($tag->posts as $post){
            $post->tags()->detach($tag->id);
        }
        $tag->delete();
        Cache::forget('tags');
        removeCache('tag');
        removeCache('post');
        removeCache('category');
        toast('Tag Deleted Successfully','success',AlertPosition());
        return redirect(route('tag.index'));
    }
    public function MultiDelete(Request $request)
    {
        //remove tag will remove all attached posts and photos inside it
        $request->validate([
            'tags_id'=>'required|array|min:1'
        ]) ;

        foreach ($request->tags_id as $id){
            $tag=Tag::find($id);
            if ($tag){
                foreach ($tag->posts as $post){
                    $post->tags()->detach($tag->id);
                }
                $tag->delete();

            } else{
                continue;
            }
        }
        Cache::forget('tags');
        removeCache('tag');
        removeCache('post');
        removeCache('category');
        toast('Selected Categories Deleted Successfully','success',AlertPosition());
        return redirect()->route('tag.index');
    }
}
