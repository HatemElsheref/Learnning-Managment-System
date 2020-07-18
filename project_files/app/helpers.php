<?php

use App\RateCourse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;


define('DS',DIRECTORY_SEPARATOR);
define('pagination',3);
define('related',3);
define('description',70);
define('app','LMS');


if (! function_exists('ReportsFeaturesMode')) {
    function ReportsFeaturesMode() {
        return true;
    }
}

if (! function_exists('SeoFeaturesMode')) {
    function SeoFeaturesMode() {
        return true;
    }
}

if (! function_exists('DeleteMode')) {    // for prevent delete university true=>enable delete
    function DeleteMode() {
        return true;
    }
}

if (! function_exists('DefaultCurrency')) {
    function DefaultCurrency() {
        return '$';
    }
}

if (! function_exists('YoutubeLessons')) {
    function YoutubeLessons() {
        return true;
    }
}

if (! function_exists('Dashboard_Assets')) {
    function Dashboard_Assets($path=null) {
        return url('dashboard/assets');
    }
}

if (! function_exists('frontend')) {
    function frontend() {
        return url('frontend/assets').'/';
    }
}

if (! function_exists('CurrentLanguage')) {
    function CurrentLanguage() {
        return app()->getLocale();
    }
}

if (! function_exists('Admin')) {
    function Admin() {
        return \Illuminate\Support\Facades\Auth::guard('webadmin');
    }
}

if (!function_exists('InitPermissions')){
    function InitPermissions($Models,$Map){
            $Ready_Permissions=[];
            foreach ($Models as $Name=>$permissions){
                foreach ($permissions as $permission){
                    $Ready_Permissions+=array(ucfirst($Map[$permission])." ".ucfirst($Name)=>$Map[$permission].'_'.$Name);
                }
            }
            return $Ready_Permissions;
    }
}

if (!function_exists('PermissionsIDs')){
    function PermissionsIDs($Permissions_name){
            return DB::table('permissions')->select('id')->whereIn('name',$Permissions_name)->pluck('id')->toArray();
    }
}

if (!function_exists('AlertPosition')){
    function AlertPosition(){
        if(app()->getLocale()=='ar'){
            return 'top-start';
        }else{
            return 'top-end';
        }
    }
}

if (!function_exists('storeAdminPermissions')){
    function storeAdminPermissions($id){
        $AuthAdmin=App\Admin::find($id);
        $AuthPermissions=$AuthAdmin->permissions;
        $permissions=[];
        foreach ($AuthPermissions as $permission){
            array_push($permissions,$permission->name);
        }
        $permissions=Cache::forever('Auth_Admin_Permissions-'.$id,$permissions);
    }
}

if (!function_exists('getAdminPermissions')){
    function getAdminPermissions(){
        $AuthAdmin=auth('webadmin')->user();
        $AuthPermissions=$AuthAdmin->permissions;
        if (Cache::has('Auth_Admin_Permissions-'.$AuthAdmin->id)) {
                   return Cache::get('Auth_Admin_Permissions-'.$AuthAdmin->id);
        }
        $permissions=[];
        foreach ($AuthPermissions as $permission){
            array_push($permissions,$permission->name);
        }
        Cache::forever('Auth_Admin_Permissions-'.$AuthAdmin->id,$permissions);
        return Cache::get('Auth_Admin_Permissions-'.$AuthAdmin->id);
    }
}

if (!function_exists('RemoveAdminPermissions')){
    function RemoveAdminPermissions(){
        $AuthAdmin=auth('webadmin')->user();
        if (Cache::has('Auth_Admin_Permissions-'.$AuthAdmin->id)) {
            Cache::forget('Auth_Admin_Permissions-'.$AuthAdmin->id);
        }
    }
}

if (!function_exists('UpdateAdminPermissions')){
function UpdateAdminPermissions($id){
    if (Cache::has('Auth_Admin_Permissions-'.$id)) {
        Cache::forget('Auth_Admin_Permissions-'.$id);
    }
    storeAdminPermissions($id);
}
}

if (!function_exists('checkDashboardPermissionFromCache')){
    function checkDashboardPermissionFromCache($permission){
        $AuthAdmin=auth('webadmin')->user();
        if (in_array($permission,Cache::get('Auth_Admin_Permissions-'.$AuthAdmin->id))){
            return true;
        }
        return false;
    }
}

if(!function_exists('checkPermissions')){
    function checkPermissions($permission){
        if (env('PERMISSION_DRIVE','database')=='cache'){
                return  checkDashboardPermissionFromCache($permission);
        }else{
            if (in_array($permission,auth('webadmin')->user()->permissions->pluck('name')->toArray())){
                return true;
            }
            return false;
        }
    }
}

if(!function_exists('checkIfCacheUsed')){
    function checkIfCacheUsed(){
        if (env('PERMISSION_DRIVE')=='cache'){
                return  true;
        }else{
            return false;
        }
    }
}

if (!function_exists('getMimeType')){
    function  getMimeType($original){
//        dd($original);
        $mimes=[
        'application/pdf'=>'pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'=>'docs',
        'application/msword'=>'doc',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation'=>'pptx',
        'application/vnd.ms-powerpoint'=>'ppt',
            'text/html'=>'HTML',
            'text/x-c'=>'PHP',
        ];
        if (in_array($original,array_keys($mimes))){
            return $mimes[$original];
        }   else{
            return 'unknown';
        }
    }
}

if (!function_exists('getExamsType')){
    function  getExamsType(){
        return [
            'tma',
            'mta',
            'final'
        ];
    }
}

if (!function_exists('getTerms')){
    function  getTerms(){
        return [
            'spring',
            'summer',
            'fall'
        ];
    }
}

if (!function_exists('getYears')){
    function  getYears(){
        $now=Carbon::now()->format('Y');
        $previousYears=25;
        $years=[];
        for ($i=0;$i<$previousYears;$i++){
            array_push($years,$now-$i);
        }
        return $years;
    }
}

if (!function_exists('CalculateCourseRate')){
    function CalculateCourseRate($course){
//        $rates=RateCourse::where('course_id','=',$course_id)->get()->groupBy('rate')->toArray() ;

        $rates=$course->rates->where('status','=','approved')->groupBy('rate')->toArray() ;

        if (count($rates)>0){
            $RATES=[];
            $total=0;
            $RATE=0;
            foreach ($rates as  $key => $_rates){
                $RATES[$key]=count($_rates);
                $total+=count($_rates);
            }
            foreach ($RATES as $key=>$value){
                $RATE+=$key*$value;
            }
            return array($RATE/$total,$total);

        }
        return array(0,0);
    }

}

if (!function_exists('CalculateDuration')){
    function CalculateDuration($id,$path){
        $path='courses_files'.DS.$id.DS.$path;
        $media=FFMpeg::open($path);
        $durationInSeconds = $media->getDurationInSeconds(); // returns an int
        $min=$durationInSeconds/60;
        settype($min,'integer');
        $seconds=$durationInSeconds%60;
        $seconds="0.".$seconds;
        settype($seconds,'float');
        return ($seconds + $min);
    }

}

if (!function_exists('CheckLessonState')){
    function CheckLessonState($course,$lesson=null){
             if ($course->isFree()){
                 return true;
             }else{
                 if (auth('web')->check()){
//                     Cache::forget('users_in_course_'.$course->id);
                     $users=Cache::rememberForever('users_in_course_'.$course->id,function () use ($course){
                               return DB::table('course_user')->select('*')
                                   ->where('course_id','=',$course->id)
                                   ->where('status','=','opened')
                                   ->pluck('user_id')->toArray();
                     });
//                     dd($users);
//                     $enrolledCourse=DB::table('course_user')->select('*')->where('course_id','=',$course->id)
//                         ->where('user_id','=',auth('web')->user()->id)->first();
//                     if ($enrolledCourse and $enrolledCourse->status=='opened'){
                     if (in_array(auth('web')->user()->id,$users)){
                         return true;
                     } else{
                         if ($lesson->status){
                             return true;
                         } else{
                             return false;
                         }
                     }
                 }else{
                     if ($lesson->status){
                         return true;
                     } else{
                         return false;
                     }
                 }
             }
    }

}

if (!function_exists('getStudentState')){         // check if user paid or enrolled in course or not
    function getStudentState($course_id){
        $enrolledCourse=DB::table('course_user')->select('*')->where('course_id','=',$course_id)
            ->where('user_id','=',auth('web')->user()->id)->first();
       if ($enrolledCourse){
           return $enrolledCourse->status;
       }  else{
               return  null;
       }

    }

}

if (!function_exists('checkIfUserHasThisCourse')){
    function checkIfUserHasThisCourse($course){ // check if user enrolled in this course or not
    if (auth('web')->check()){
        $enrolledCourse=DB::table('course_user')->select('*')
            ->where('course_id','=',$course->id)
            ->where('user_id','=',auth('web')->user()->id)->first();
        if ($enrolledCourse){
            return $enrolledCourse->status;
        }
        return false;
    }   else{
        return false;
    }

}

}


function removeCache($model){
         switch ($model){
             case 'feedback':
                 Cache::forget('feedbackImages_Videos');
                 Cache::forget('feedbackAudios');
                 Cache::forget('universities') ;
                 Cache::forget('departments') ;
                 Cache::forget('dashboard_statistics') ;
                 break;
             case 'post':
                 Cache::forget('categories') ;
                 Cache::forget('tags') ;
                Cache::forget('recent_posts ') ;
                 Cache::forget('dashboard_statistics') ;
                 break;
             case 'category':
                 Cache::forget('categories') ;
                 Cache::forget('tags') ;
                 Cache::forget('recent_posts ') ;
                 Cache::forget('dashboard_statistics') ;
                 break;
             case 'tag':
                 Cache::forget('categories') ;
                 Cache::forget('tags') ;
                 Cache::forget('recent_posts ') ;
                 Cache::forget('dashboard_statistics') ;
                 break;
             case 'department':
                 Cache::forget('universities') ;
                 Cache::forget('feedbackImages_Videos');
                 Cache::forget('feedbackAudios');
                 Cache::forget('departments') ;
                 Cache::forget('total_courses') ;
                 Cache::forget('recent_courses') ;
                 Cache::forget('dashboard_statistics') ;
                 Cache::forget('orders') ;
                 break;
             case 'university':
                 Cache::forget('universities') ;
                 Cache::forget('feedbackImages_Videos');
                 Cache::forget('feedbackAudios');
                 Cache::forget('departments') ;
                 Cache::forget('total_courses') ;
                 Cache::forget('recent_courses') ;
                 Cache::forget('dashboard_statistics') ;
                 Cache::forget('orders') ;
                 break;
             case 'project':
                 Cache::forget('projects') ;
                 Cache::forget('dashboard_statistics') ;
                 break;
             case 'user':
                 Cache::forget('dashboard_statistics') ;
                 Cache::forget('orders') ;
                 break;
             case 'instructor':
                 Cache::forget('dashboard_statistics') ;
                 Cache::forget('total_courses') ;
                 Cache::forget('recent_courses') ;
                 Cache::forget('orders') ;
                 Cache::forget('categories') ;
                 Cache::forget('tags') ;
                 Cache::forget('recent_posts ') ;
                 break;
             case 'course':
                 Cache::forget('dashboard_statistics') ;
                 Cache::forget('total_courses') ;
                 Cache::forget('recent_courses') ;
                 Cache::forget('orders') ;
                 break;
             case 'order':
                 Cache::forget('dashboard_statistics') ;
                 Cache::forget('orders') ;
                 break;
         }
}
