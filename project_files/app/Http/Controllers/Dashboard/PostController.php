<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Http\Controllers\Controller;
use App\Instructor;
use App\Lesson;
use App\LessonFile;
use App\Post;
use App\Tag;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    public function __construct() {
        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:read_post')->only('index');
        $this->middleware('DashboardPermission:create_post')->only(['create','store']);
        $this->middleware('DashboardPermission:update_post')->only(['edit','update']);
        $this->middleware('DashboardPermission:delete_post')->only(['destroy','MultiDelete']);
    }
    public function index(){
        $posts=Post::with('instructor','category')->orderBy('created_at','desc')->get();
          return view('dashboard.blog.posts.index',compact('posts'));
    }

    public function create(){
        $categories=Category::all();
        if (count($categories)==0){
            toast('You Must Create Category First ','info',AlertPosition());
            return redirect()->route('category.index');
        }
        $instructors=Instructor::all();
        if (count($instructors)==0){
            toast('You Must Create Instructor First ','info',AlertPosition());
            return redirect()->route('instructor.create');
        }
        $tags=Tag::all();
        return view('dashboard.blog.posts.create',['tags'=>$tags,'categories'=>$categories,'instructors'=>$instructors]);
    }

    public function store(Request $request){

        $request->validate([
            'title'=>'required|string|max:191',
            'description'=>'required|string',
            'slug'=>'required|string|max:191|unique:posts,slug',
            'photo'=>'required|image|mimes:jpg,png,jpeg',
            'content'=>'required|string',
            'dir'=>'in:ltr,rtl',
            'category_id'=>'required|numeric',
            'instructor_id'=>'required|numeric',
            'tags' => 'array',
            'meta_description'=>'min:0',
            'meta_title'=>'max:191|min:0',
            'meta_keywords'=>'array|min:0'
        ]);

        $category=Category::find($request->category_id);
        if (!$category){
            toast('Category Not Found','error',AlertPosition());
            return redirect()->route('post.create');
        }
        $instructor=Instructor::find($request->instructor_id);
        if (!$instructor){
            toast('Instructor Not Found','error',AlertPosition());
            return redirect()->route('post.create');
        }


        $validated_data['title']=$request->title;
        $validated_data['description']=$request->description;
        $validated_data['content']=$request->get('content');
        $validated_data['slug']=$request->slug;
        $validated_data['meta_description']=$request->meta_description;
        $validated_data['meta_title']=$request->meta_title;
        $validated_data['category_id']=$category->id;
        $validated_data['instructor_id']=$instructor->id;
        $validated_data['meta_keywords']=json_encode($request->meta_keywords);

        if ($request->has('status') and $request->status=='published'){
            $validated_data['status']=true;
        }else{
            $validated_data['status']=false;
        }
        if ($request->has('dir')){
            $validated_data['dir']=$request->dir;
        }else{
            $validated_data['dir']='ltr';
        }


        if ($request->hasFile('photo') and !empty($request->file('photo'))) {
            $request->file('photo')->storeAs('posts_photos',$request->file('photo')->hashName());
            $validated_data['photo']=$request->file('photo')->hashName();
        }  else{
            toast('Post Main Photo Required','warning',AlertPosition());
            return redirect()->route('post.create')->withInput('Post Main Photo Required');
        }
        $post=Post::create($validated_data);

        if ($post){
            Cache::forget('categories');
            Cache::forget('tags');
            Cache::forget('recent_posts');
            removeCache('tag');
            removeCache('post');
            removeCache('category');
            if (!empty($request->tags)){
                $post->tags()->attach($request->tags);
            }
            toast('Post Added Successfully','success',AlertPosition());
            return redirect()->route('post.index');
        }   else{
            toast('Failed To Add Post','error',AlertPosition());
            return redirect()->route('post.index');
        }
    }

    public function edit($id){
        $post=Post::find($id);
        if (!$post){
             toast('Post Not Found','error',AlertPosition());
             return redirect()->route('post.index');
        }
        $instructors=Instructor::all();
        $categories=Category::all();
        $tags=Tag::all();
        return view('dashboard.blog.posts.edit',compact('post'))
            ->with('categories',$categories)
            ->with('instructors',$instructors)
            ->with('tags',$tags);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title'=>'required|string|max:191',
            'description'=>'required|string',
            'slug'=>'required|string|max:191|unique:posts,slug,'.$id,
            'photo'=>'image|mimes:jpg,png,jpeg',
            'content'=>'required|string',
            'dir'=>'in:ltr,rtl',
            'category_id'=>'required|numeric',
            'instructor_id'=>'required|numeric',
            'tags' => 'array',
            'meta_description'=>'min:0',
            'meta_title'=>'max:191|min:0',
            'meta_keywords'=>'array|min:0'
        ]);
           $post=Post::find($id);
        if (!$post){
            toast('Post Not Found','error',AlertPosition());
            return redirect()->route('post.index');
        }
        $category=Category::find($request->category_id);
        if (!$category){
            toast('Category Not Found','error',AlertPosition());
            return redirect()->route('post.create');
        }
        $instructor=Instructor::find($request->instructor_id);
        if (!$instructor){
            toast('Instructor Not Found','error',AlertPosition());
            return redirect()->route('post.create');
        }


        $validated_data['title']=$request->title;
        $validated_data['description']=$request->description;
        $validated_data['content']=$request->get('content');
        $validated_data['slug']=$request->slug;
        $validated_data['meta_description']=$request->meta_description;
        $validated_data['meta_title']=$request->meta_title;
        $validated_data['category_id']=$category->id;
        $validated_data['instructor_id']=$instructor->id;
        $validated_data['meta_keywords']=json_encode($request->meta_keywords);


        if ($request->has('status') and $request->status=='published'){
            $validated_data['status']=true;
        }else{
            $validated_data['status']=false;
        }


        if ($request->has('dir')){
            $validated_data['dir']=$request->dir;
        }else{
            $validated_data['dir']=$post->dir;
        }

        if ($request->hasFile('photo') and !empty($request->file('photo'))) {
            Storage::disk('local')->delete('posts_photos'.DIRECTORY_SEPARATOR.$post->photo);
            $request->file('photo')->storeAs('posts_photos',$request->file('photo')->hashName());
            $validated_data['photo']=$request->file('photo')->hashName();
        }  else{
            $validated_data['photo']=$post->photo;
        }

        if ($post->update($validated_data)){
            Cache::forget('categories');
            Cache::forget('tags');
            Cache::forget('recent_posts');
            removeCache('tag');
            removeCache('post');
            removeCache('category');
            if (!empty($request->tags)){
                $post->tags()->sync($request->tags);
            }  else{
                $post->tags()->detach();
            }
            toast('Post Updated Successfully','success',AlertPosition());
            return redirect()->route('post.index');
        }   else{
            toast('Failed To Update Post','error',AlertPosition());
            return redirect()->route('post.index');
        }
    }

    public function destroy($id){
        $post=Post::find($id);
        if ($post){
            Storage::disk('local')->delete('posts_photos'.DIRECTORY_SEPARATOR.$post->photo);
            $post->tags()->detach();
            $post->delete();
            Cache::forget('categories');
            Cache::forget('tags');
            Cache::forget('recent_posts');
            removeCache('tag');
            removeCache('post');
            removeCache('category');
            toast('Post Deleted Successfully','success',AlertPosition());
            return redirect()->route('post.index');
        }
        toast('Post Not Found','error',AlertPosition());
        return redirect()->route('post.index');
    }

    public function MultiDelete(Request $request)
    {
        $request->validate([
            'posts_id'=>'required|array|min:1'
        ]) ;

        foreach ($request->posts_id as $id){
            $post=Post::find($id);
            if ($post){
                Storage::disk('local')->delete('posts_photos'.DIRECTORY_SEPARATOR.$post->photo);
                $post->tags()->detach();
                $post->delete();

            }    else{
                continue;
            }

        }
        Cache::forget('categories');
        Cache::forget('tags');
        Cache::forget('recent_posts');
        removeCache('tag');
        removeCache('post');
        removeCache('category');
        toast('Selected Posts Deleted Successfully','success',AlertPosition());
        return redirect()->route('post.index');

    }


}
