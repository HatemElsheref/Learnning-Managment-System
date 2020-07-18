<?php

namespace App\Http\Controllers\Dashboard;

use App\Admin;
use App\Article;
use App\course;
use App\Lesson;
use App\CourseFile;
use App\LessonFile;
use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use App\Category;
use App\Department;
use App\Instructor;
use App\Tag;
use App\Feedback;
use App\Project;
use App\University;
use Carbon\Carbon;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
   use NotificationTrait;
    public function index(){
        $dashboard_statistics=Cache::rememberForever('dashboard_statistics',function (){
            $data=[];
            $data['free_courses']=course::where('price','=',1)->count();
            $data['paid_courses']=course::where('price','!=',0)->count();
            $data['free_lessons']=Lesson::where('status','=',1)->count();
            $data['paid_lessons']=Lesson::where('status','=',0)->count();
            $data['global_exams']=CourseFile::where('status','=','opened')->count();
            $data['paid_exams']=CourseFile::where('status','=','closed')->count();
            $data['free_files']=LessonFile::where('isFree','=',1)->count();
            $data['paid_files']=LessonFile::where('isFree','=',0)->count();
            $data['universities']=University::count();
            $data['departments']=Department::count();
            $data['articles']=Article::count();
            $data['posts']=Post::count();
            $data['tags']=Tag::count();
            $data['categories']=Category::count();
            $data['instructors']=Instructor::count();
            $data['staff']=Admin::where('role','=','staff')->count();
            $data['students']=User::count();
            $data['feedback']=Feedback::count();
            $data['projects']=Project::count();
            $data['rate_courses']=DB::table('rate_courses')->count();
            $data['rate_lessons']=DB::table('rate_lessons')->count();
            $data['paid_orders']=DB::table('course_user')->where('status','=','opened')->count();
            $data['pending_orders']=DB::table('course_user')->where('status','=','closed')->count();
            $cols=[DB::raw('courses.price'),'course_user.created_at'];
            $statistics=DB::table('course_user')->select($cols)->whereYear('course_user.created_at','=',date('Y'))
                ->where('status','=','opened')
                ->join('courses', function ($join) {
                    $join->on('course_user.course_id', '=', 'courses.id');
                })->get()
                ->groupBy(function($val) {
                    return Carbon::parse($val->created_at)->format('M');
                });
            $chart=[
                "Jan"=>0,
                "Feb"=>0,
                "Mar"=>0,
                "Apr"=>0,
                "May"=>0,
                "Jun"=>0,
                "Jul"=>0,
                "Aug"=>0,
                "Sep"=>0,
                "Oct"=>0,
                "Nov"=>0,
                "Dec"=>0
            ];
            $total_orders=0;
            $total_price=0;
            foreach ($statistics as $key=>$items) {
                $price=0;
                foreach ($items as $item){
                    $price+=$item->price;
                    $price==0?null:$total_orders++;
                }
                $chart[$key]=$price;
                $total_price+=$price;
            }
            $data['chart']=$chart;
            $data['total_orders']=$total_orders;
            $data['total_price']=$total_price;
            return $data;
        });


        return view('dashboard.index', $dashboard_statistics);
    }



    public function downloadLocalExams($course_id,$file_name,$new_file_name){

        $filePath='app/courses_files'.DS.$course_id.DS.'exams'.DS.$file_name;
        if (file_exists(storage_path($filePath))) {
            $type=Storage::disk('local')->mimeType('courses_files'.DS.$course_id.DS.'exams'.DS.$file_name);
            $extension=explode('.',$file_name);
            $extension=end($extension);
            $new_file_name=$new_file_name.'.'.$extension;
            return response()->download(storage_path($filePath),$new_file_name,['contentType',$type]);
        }
        abort(404);
    }
    public function downloadCloudExams($file_path,$new_file_name){
       $url=base64_decode($file_path);
       $extension=explode('.',$url);
       $extension=end($extension);
        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=".$new_file_name.'.'.$extension);
        echo readfile($url);
    }
    public function downloadLocalAttachedFiles($course_id,$file_name,$new_file_name){
        $filePath='app/courses_files'.DS.$course_id.DS.'files'.DS.$file_name;
        if (file_exists(storage_path($filePath))) {
            $type=Storage::disk('local')->mimeType('courses_files'.DS.$course_id.DS.'files'.DS.$file_name);
            $extension=explode('.',$file_name);
            $extension=end($extension);
            $new_file_name=$new_file_name.'.'.$extension;
            return response()->download(storage_path($filePath),$new_file_name,['contentType',$type]);

        }
          abort(404);
    }
    public function downloadCloudAttachedFiles($file_path,$new_file_name){
        $url=base64_decode($file_path);
        $extension=explode('.',$url);
        $extension=end($extension);
        header("Content-Type: application/octet-stream");
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=".$new_file_name.'.'.$extension);
        echo readfile($url);
    }














//     public function uploadedFiles($model,$name,$id=null,$type=null){
//         if ($id!=null){
//             if ($type!=null){
//                 $DIR=$model.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR;
//             }else{
//                 $DIR=$model.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR;
//             }
//         }else{
//             $DIR=$model.DIRECTORY_SEPARATOR;
//         }
//
//         $filePath=$DIR.$name;
//         if (file_exists(storage_path('app'.DS.$filePath))){
//             $file=Storage::disk('local')->get($filePath);
//             $type=Storage::disk('local')->mimeType($filePath);
//             $response = response()->make($file, 200);
//             $response->header("Content-Type",$type);
//             return $response;
//         }
//           return null;
//     }
//    public function downloadFiles($id,$name,$type='files'){
//
//        $filePath='app/courses_files'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$name;
//        return response()->download(storage_path($filePath));
//
//    }
}
