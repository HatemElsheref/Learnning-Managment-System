<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    public function __construct() {

        $this->middleware('DashboardPermission:read_category')->only('index');
        $this->middleware('DashboardPermission:create_category')->only(['create','store']);
        $this->middleware('DashboardPermission:update_category')->only(['update','edit']);
        $this->middleware('DashboardPermission:delete_category')->only(['destroy','MultiDelete']);
    }

    public function index(){
        $categories=Category::with('posts')->get();
        return view('dashboard.blog.posts_category.index',['categories'=>$categories]);
    }
    public function create(){
        return view('dashboard.blog.posts_category.create');
    }
    public function store(Request $request){
        $request->validate([
            'name'=>'required|unique:categories,name'
        ]);
        $category=Category::create($request->all());
        Cache::forget('categories');
        removeCache('category');
        if ($category){
            toast('Category Created Successfully','success',AlertPosition());
            return redirect(route('category.index'));
        }
        else{
            toast('Failed To Create Category','error',AlertPosition());
            return redirect(route('category.create'));
        }
    }
    public function edit(Category $category){
        if ($category) {
            return view('dashboard.blog.posts_category.edit', compact('category'));
        }else{
            toast('Category Not Found ','error',AlertPosition());
            return redirect()->route('category.index');
        }
    }
    public function update(Request $request,Category $category){
       if (!$category){
           toast('Category Not Found','error',AlertPosition());
           return redirect()->route('category.index');
       }
        $request->validate([
            'name'=>'required|string|max:191|unique:categories,name,'.$category->id
        ]);
       $validated_data['name']=$request->name;
        $category->update($validated_data);
        Cache::forget('categories');
        removeCache('category');
        toast('Category Updated Successfully','success',AlertPosition());
        return redirect(route('category.index'));
    }
    public function destroy(Category $category){
        //remove category will remove all attached posts and photos inside it
        if (!$category) {
            toast('Category Not Found','error',AlertPosition());
            return redirect()->route('category.index');
        }
//        $category->posts()->delete();
        foreach ($category->posts as $post){
            Storage::disk('local')->delete('posts_photos'.DIRECTORY_SEPARATOR.$post->photo);
            $post->delete();
        }
        $category->delete();
        Cache::forget('categories');
        removeCache('category');
        toast('Category Deleted Successfully','success',AlertPosition());
        return redirect(route('category.index'));
    }
    public function MultiDelete(Request $request)
    {
        //remove category will remove all attached posts and photos inside it
        $request->validate([
            'categories_id'=>'required|array|min:1'
        ]) ;

        foreach ($request->categories_id as $id){
            $category=Category::find($id);
            if ($category){
                foreach ($category->posts as $post){
                    Storage::disk('local')->delete('posts_photos'.DIRECTORY_SEPARATOR.$post->photo);
                    $post->delete();
                }
                $category->delete();
            } else{
                continue;
            }
        }
        Cache::forget('categories');
        removeCache('category');
        toast('Selected Categories Deleted Successfully','success',AlertPosition());
        return redirect()->route('category.index');
    }

}
