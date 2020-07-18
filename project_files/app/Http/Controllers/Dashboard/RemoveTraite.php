<?php


namespace App\Http\Controllers\Dashboard;


use App\Feedback;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait RemoveTraite
{

    private static function Path($COURSE_ID){
        return 'courses_files'.DIRECTORY_SEPARATOR.$COURSE_ID.DIRECTORY_SEPARATOR;
    }

    public static function RemoveLesson($lesson){
          //remove all attached files
           foreach ($lesson->files as $file){
               if($file->hosting=='local'){
                   Storage::disk('local')->delete(self::Path($lesson->part->course->id).'files'.DIRECTORY_SEPARATOR.$file->path);
               }
                 $file->delete();
           }
        foreach ($lesson->rates as $rate){
            $rate->delete();
        }

    }

    public static function RemovePart($part){
        foreach ($part->lessons as $lesson){
              self::RemoveLesson($lesson);
//              if (YoutubeLessons()){
            if ($lesson->type=='local'){
                  Storage::disk('local')->delete(self::Path($lesson->part->course->id).$lesson->video);
              }
              $lesson->delete();
        }
    }

    public static function RemoveCourse($course){
        //remove all attached files  of parts
        foreach ($course->parts as $part){
            self::RemovePart($part);
            $part->delete();
        }
        //remove all attached files  of exams
        foreach ($course->files as $file){
            if ($file->hosting=='local') {
                Storage::disk('local')->delete(self::Path($course->id).'exams'.DIRECTORY_SEPARATOR.$file->path);
            }
            $file->delete();
        }
        //remove all attached photos
        $attachedPhotos=DB::table('uploads')->select('*')
            ->where('parent','=','course')
            ->where('parent_id','=',$course->id)
            ->get();
        foreach($attachedPhotos as $photo){
            Storage::disk('local')->delete(self::Path($course->id).'photos'.DIRECTORY_SEPARATOR.$photo->path);
        }
        DB::table('uploads')->select('*')
            ->where('parent','=','course')
            ->where('parent_id','=',$course->id)
            ->delete();
        //remove all attached articles
        foreach ($course->articles as $article){
            Storage::disk('local')->delete(self::Path($course->id).'photos'.DIRECTORY_SEPARATOR.$article->path);
            $article->delete();
        }
        DB::table('course_user')
            ->where('course_id','=',$course->id)->delete();

        File::deleteDirectory(storage_path('app'.DIRECTORY_SEPARATOR.self::Path($course->id).'files'));
        File::deleteDirectory(storage_path('app'.DIRECTORY_SEPARATOR.self::Path($course->id).'exams'));
        File::deleteDirectory(storage_path('app'.DIRECTORY_SEPARATOR.self::Path($course->id).'photos'));
        File::deleteDirectory(storage_path('app'.DIRECTORY_SEPARATOR.self::Path($course->id)));
        foreach ($course->rates as $rate){
            $rate->delete();
        }
        DB::table('course_user')->where('course_id','=',$course->id)->delete();
        Cache::forget('orders');
        Cache::forget('recent_courses');
        Cache::forget('total_courses');
        removeCache('course');
    }

    public static function RemoveInstructor($instructor){
          foreach ($instructor->courses as $course){
              self::RemoveCourse($course);
              Storage::disk('local')->delete(self::Path($course->id).'photos'.DIRECTORY_SEPARATOR.$course->photo);
             if (file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'courses_files'.DIRECTORY_SEPARATOR.$course->id.DIRECTORY_SEPARATOR.$course->video))){
                 Storage::disk('local')->delete(self::Path($course->id).$course->video);
             }
              $course->delete();
          }
        Cache::forget('recent_courses');
        Cache::forget('total_courses');


          foreach ($instructor->posts as $post){
              Storage::disk('local')->delete('posts_photos'.DIRECTORY_SEPARATOR.$post->photo);
              $post->tags()->detach();
              $post->delete();
          }
        removeCache('instructor');
        removeCache('course');
        removeCache('orders');
    }

    public static function RemoveDepartment($department){
        foreach ($department->courses as $course){
            self::RemoveCourse($course);
            Storage::disk('local')->delete(self::Path($course->id).'photos'.DIRECTORY_SEPARATOR.$course->photo);
            if (file_exists(storage_path('app'.DIRECTORY_SEPARATOR.'courses_files'.DIRECTORY_SEPARATOR.$course->id.DIRECTORY_SEPARATOR.$course->video))){
                Storage::disk('local')->delete(self::Path($course->id).$course->video);
            }
            $course->delete();
        }


        Cache::forget('recent_courses');
        Cache::forget('departments');
        Cache::forget('universities');
        Cache::forget('total_courses');
        Cache::forget('feedbackImages_Videos');
        Cache::forget('feedbackAudios');
        $paths=[
          'audio'=>'audios',
          'image'=>'images',
          'video'=>'videos'
        ];
        foreach ($department->feedbacks as $feedback){
            Storage::disk('local')->delete('feedback'.DIRECTORY_SEPARATOR.$paths[$feedback->type].DIRECTORY_SEPARATOR.$feedback->feedback);
            $feedback->delete();
        }

        // delete all users in this department
        foreach ($department->users as $user){
            if ($user->photo != 'default.png'){
                Storage::disk('local')->delete('users'.DS.$user->photo);
            }
            DB::table('course_user')->where('user_id','=',$user->id)->delete();
            $user->delete();
        }
          removeCache('department');
        removeCache('university');
        removeCache('course');
        removeCache('orders');
        removeCache('user');
        removeCache('feedback');
    }

    public static function RemoveUniversity($university){
        foreach ($university->departments as $department){
            self::RemoveDepartment($department);
            Storage::disk('local')->delete('departments_avatars'.DIRECTORY_SEPARATOR.$department->photo);
              $department->delete();
        }
//        Cache::forget('topCourses');
        Cache::forget('recent_courses');
        Cache::forget('total_courses');
        Cache::forget('departments');
        Cache::forget('universities');

        removeCache('department');
        removeCache('university');
        removeCache('course');
        removeCache('orders');
        removeCache('user');
        removeCache('feedback');
    }

    public static function RemoveFeedback($departmentId){
          $feedback=Feedback::where('department_id','=',$departmentId)->get();
          $path=[
              'image'=>'images',
              'audio'=>'audios',
              'video'=>'videos',
          ];
          if ($feedback){
              Storage::disk('local')->delete('feedback'.DIRECTORY_SEPARATOR.$path[$feedback->type].DIRECTORY_SEPARATOR.$feedback->feedback);
              $feedback->delete();
          }
        removeCache('feedback');
    }
}
