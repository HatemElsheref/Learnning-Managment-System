<?php

namespace App\Http\Controllers\Dashboard;

use App\Article;
use App\course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    CONST _PATH='courses_files';

    public function __construct() {

        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:read_article')->only('show');
        $this->middleware('DashboardPermission:create_article')->only(['create','store']);
        $this->middleware('DashboardPermission:update_article')->only(['edit','update']);
        $this->middleware('DashboardPermission:delete_article')->only(['destroy','MultiDelete']);

    }
    public function create($id)
    {
        $course=Course::find($id);
        if ($course){
            return view('dashboard.course.articles.create')
                ->with('course',$course);
        }else{
            toast('Course Not Found','error',AlertPosition());
            return redirect()->route('dashboard');
        }

    }

    public function store(Request $request)
    {

        $request->validate([
            'course_id'=>'required|numeric|min:0',
            'title'=>'required|string|max:191',
            'subtitle' => 'required|string',
            'slug'=>'required|string|max:191|unique:articles,slug',
            'photo'=>'required|image:png,jpg,jpeg',
            'tags' => 'array',
            'content' => 'required|string',
            'dir'=>'in:ltr,rtl',
            'meta_description'=>'min:0',
            'meta_title'=>'max:191|min:0',
            'meta_keywords'=>'array|min:0'
        ]);
             $course=course::find($request->course_id);
             if (!$course){
                  toast('Course Not Found','error',AlertPosition());
                  return redirect()->route('dashboard');
             }

             $author=$course->instructor_id;
             $validated_data=$request->except(['photo','tags','meta_keywords']);
             $validated_data['course_id']=$course->id;
             $validated_data['instructor_id']=$author;
             $validated_data['tags']=json_encode($request->tags);
             $validated_data['meta_keywords']=json_encode($request->meta_keywords);

        if ($request->has('dir')){
            $validated_data['dir']=$request->dir;
        }else{
            $validated_data['dir']='ltr';
        }



        if ($request->hasFile('photo') and !empty($request->file('photo'))) {
                  $request->file('photo')->storeAs($this->getCoursePath($course->id).DIRECTORY_SEPARATOR.'photos',$request->file('photo')->hashName());
                  $validated_data['photo']=$request->file('photo')->hashName();
             }  else{
                 toast('Article Main Photo Required','warning',AlertPosition());
                 return redirect()->route('course.article.create',$course->id);
             }
             $article=Article::create($validated_data);
             if ($article){
                 removeCache('course');
                 toast('Article Added Successfully','success',AlertPosition());
                 return redirect()->route('course.article.show',$course->id);
             }   else{
                 toast('Failed To Add Article','error',AlertPosition());
                 return redirect()->route('course.article.create',$course->id);
             }
    }

    public function show($id)
    {

        $course=course::find($id);
        if (!$course){
            toast('Course Not Found','error',AlertPosition());
            return redirect()->route('dashboard');
        } else{
            $articles=Article::where('course_id','=',$id)->get();
            return view('dashboard.course.articles.show',[
                'articles'=>$articles ,'course'=>$course
            ]);
        }


    }

    public function edit($id)
    {
        $article=Article::find($id);
        if ($article){
            return view('dashboard.course.articles.edit')
                ->with('article',$article);
        }else{
            toast('Article Not Found','error',AlertPosition());
            return redirect()->route('dashboard');
        }
    }

    public function update(Request $request,$id)
    {

        $request->validate([
            'course_id'=>'required|numeric|min:0',
            'title'=>'required|string|max:191',
            'subtitle' => 'required|string',
            'slug'=>'required|string|max:191|unique:articles,slug,'.$id,
            'photo'=>'image:png,jpg,jpeg',
            'tags' => 'array',
            'content' => 'required|string',
            'dir'=>'in:ltr,rtl',
            'meta_description'=>'min:0',
            'meta_title'=>'max:191|min:0',
            'meta_keywords'=>'array|min:0'
        ]);
        $article=Article::find($id);
        if (!$article){
            toast('Article Not Found','error',AlertPosition());
            return redirect()->route('dashboard');
        }
        $course=course::find($request->course_id);
        if (!$course){
            toast('Course Not Found','error',AlertPosition());
            return redirect()->route('dashboard');
        }

        $author=$course->instructor_id;
        $validated_data=$request->except(['photo','tags','meta_keywords']);
        $validated_data['course_id']=$course->id;
        $validated_data['instructor_id']=$author;
        $validated_data['tags']=json_encode($request->tags);
        $validated_data['meta_keywords']=json_encode($request->meta_keywords);


        if ($request->has('dir')){
            $validated_data['dir']=$request->dir;
        }else{
            $validated_data['dir']=$article->dir;
        }

        if ($request->hasFile('photo') and !empty($request->file('photo'))) {
            Storage::disk('local')->delete($this->getCoursePath($course->id).DIRECTORY_SEPARATOR.'photos'.DIRECTORY_SEPARATOR.$article->photo);
            $request->file('photo')->storeAs($this->getCoursePath($course->id).DIRECTORY_SEPARATOR.'photos',$request->file('photo')->hashName());
            $validated_data['photo']=$request->file('photo')->hashName();
        }  else{
            $validated_data['photo']=$article->photo;
        }

        if ($article->update($validated_data)){
            removeCache('course');
            toast('Article Updated Successfully','success',AlertPosition());
            return redirect()->route('course.article.show',$course->id);
        }   else{
            toast('Failed To Update Article','error',AlertPosition());
            return redirect()->route('course.article.show',$course->id);
        }

    }

    public function destroy($id)
    {
        $article=Article::find($id);
        if (!$article){
            toast('Article Not Found','error',AlertPosition());
            return redirect()->route('dashboard');
        } else{
            removeCache('course');
            $temp_course_id=$article->course_id;
            Storage::disk('local')->delete($this->getCoursePath($article->course_id).DIRECTORY_SEPARATOR.'photos'.DIRECTORY_SEPARATOR.$article->photo);
            $article->delete();
            toast('Article Deleted Successfully','success',AlertPosition());
            return redirect()->route('course.article.show',$temp_course_id);
        }

    }

    public function MultiDelete(Request $request)
    {

        $request->validate(['articles_id' => 'required|array|min:1']);

        foreach ($request->articles_id as $id) {
            $article = Article::find($id);
            if ($article) {
                $temp_course_id = $article->course_id;
                Storage::disk('local')->delete($this->getCoursePath($article->course_id) . DIRECTORY_SEPARATOR . 'photos' . DIRECTORY_SEPARATOR . $article->photo);
                $article->delete();
            } else {
                continue;
            }
        }
        removeCache('course');
        toast('Selected Articles Deleted Successfully','success',AlertPosition());
        return redirect()->route('course.article.show',$temp_course_id);
    }

    private function getCoursePath($courseid){
        return  self::_PATH.DIRECTORY_SEPARATOR.$courseid;
    }
}
