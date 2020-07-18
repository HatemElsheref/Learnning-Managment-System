<?php

namespace App\Http\Controllers\Dashboard;
use App\course;
use App\Upload;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UploaderController extends Controller
{
//    private  $Models=['project','course','category'];
    private  $Models=['project','course'];
    private $allowed_files=[
                                        'images'=>['image/jpeg','image/png','image/jpg','gif'],
                                        'files'=>[
                                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword',
                                        'text/csv',
                                        'application/pdf',
                                        'application/xhtml+xml','application/xhtml+xml',
                                        'application/vnd.ms-powerpoin','application/vnd.openxmlformats-officedocument.presentationml.presentation'
                                        ],
                                        'videos'=>['video/x-msvideo','video/mpeg','video/ogg','video/mp2t','	video/webm','video/mp4'],
                                        'sounds'=>['audio/mpeg','	audio/mp4','audio/x-aiff']
    ];

    public function __construct() {
        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:create_photos')->only(['getUploadForm','upload']);
        $this->middleware('DashboardPermission:update_photos')->only('updateStatus');
        $this->middleware('DashboardPermission:delete_photos')->only(['destroy','MultiDelete']);
    }

    public function getUploadForm($model,$id){
        if(in_array(strtolower($model),$this->Models)){
            return view('dashboard.uploader.uploader',['model'=>$model,'id'=>$id]);
        }else{
            toast('Undefined Model','error',AlertPosition());
            return  redirect()->route('dashboard');
        }
    }
    public function upload(Request $request,$model,$id){
        if(in_array(strtolower($model),$this->Models)){

            $_path=null;
            $_parent=null;
            $_model=null;
            $_allowed=null;

                switch ($model){
                    case 'course':
                            $_model=\App\Course::find($id);
                            $_path='courses_files';
//                            $_allowed=['images','files'];
                            $_allowed=['images'];
                            $_parent='course';
                        break;
                    case 'project':
                           $_model=\App\Project::find($id);
                           $_path='projects_photos';
                           $_allowed=['images'];
                           $_parent='project';
                        break;
                    case 'category':
                           $_model=\App\Category::find($id);
                           $_path='categories_photos';
                           $_allowed=['images'];
                           $_parent='category';
                         break;
                    default:
                        return response()->json(['message'=>'Undefined Model','status'=>false]);
                        break;
                }
                $mimes_allowed_for_current_model=[];
                foreach ($_allowed as $_allow){
                    foreach ($this->allowed_files[$_allow] as $mime){
                        array_push($mimes_allowed_for_current_model,$mime);
                    }
                }

                if ($_model){
                    $file = $request->file('file');
                    $fileMime=$file->getClientMimeType();
                    $filename =$file->hashName();
                    if (in_array($fileMime,$mimes_allowed_for_current_model)){   //this mean that file is allowed to upload and save in db
                        $sub_path=$this->getMimePath($fileMime);
                        $_path=$_path.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.$sub_path;
                    } else{
                        return response()->json(['message'=>'UnAllowed File','status'=>false]);
                    }
                    $file->storeAs($_path,$filename);
                    $fileUpload = new Upload();
                    $fileUpload->status = false;
                    $fileUpload->path = $filename;
                    $fileUpload->parent_id = $id;
                    $fileUpload->parent = $_parent;
                    $fileUpload->mimes =$this->getShortMimeName($fileMime);
                    $fileUpload->save();
                    if ($_parent=='course'){
                        removeCache('course');
                    } else{
                        removeCache('project');
                    }


                    return response()->json(['message'=>'Uploaded Successfully','status'=>true]);
                } else{
                    return response()->json(['message'=>'Filed To Upload','status'=>false]);
                }

        }


    }

    private function getMimePath($mime){
                 if (in_array($mime,$this->allowed_files['images'])) {
                    return 'photos';
                 } elseif (in_array($mime,$this->allowed_files['files'])){
                     return 'files';
                 }elseif (in_array($mime,$this->allowed_files['videos'])){
                     return 'videos';
                 }elseif (in_array($mime,$this->allowed_files['sounds'])){
                     return 'sounds';
                 }else{
                     return '';
                 }
    }
    private function getShortMimeName($mime){
        if (in_array($mime,$this->allowed_files['images'])) {
            return 'image';
        } elseif (in_array($mime,$this->allowed_files['files'])){
            return 'file';
        }elseif (in_array($mime,$this->allowed_files['videos'])){
            return 'video';
        }else{
            return 'sound';
        }
    }

    public function updateStatus($id){
       if ( $file=Upload::find($id)){
           if ($file->status){
               $file->status=false;
               $file->save();
               toast('Photo Status Updated Successfully','success',AlertPosition());
                     return redirect()->back();
           }
           $file->status=true;
           $file->save();
           toast('Photo Status Updated Successfully','success',AlertPosition());
           return redirect()->back();
       }   else{
           toast('Incorrect ID','error',AlertPosition());
           return redirect()->back();
       }
    }

    public function destroy($id)
    {
        $models=['project'=>'projects_photos','course'=>'courses_files','category'=>'categories_photos'];
        $types=['image'=>'photos','file'=>'files','sound'=>'sounds','video'=>'videos'];
        $parent_id=null;
        $model=null;
        if ($file=Upload::find($id)){
            $parent_id=$file->parent_id;
            $model=$file->parent;
            $path=$models[$file->parent];
            $type=$types[$file->mimes];
            $fullPath=$path.DIRECTORY_SEPARATOR.$file->parent_id.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$file->path;
            Storage::disk('local')->delete($fullPath);
            $file->delete();
            toast('Photo Deleted Successfully','success',AlertPosition());
            if ($model=='category'){
//                return redirect()->route('category.index');
                return redirect()->back();
            }else{
//                return redirect()->route($model.'.show',$parent_id);
                return redirect()->back();
            }
        }else{
            toast('Failed To Delete Photo','error',AlertPosition());
            if ($model=='category'){
//                return redirect()->route('category.index');
                return redirect()->back();
            }else{
//                return redirect()->route($model.'.show',$parent_id);
                return redirect()->back();
            }
        }
    }

    public function MultiDelete(Request $request)
    {

        $request->validate([
            'photos_id'=>'required|array|min:1'
        ]) ;
        $models=['project'=>'projects_photos','course'=>'courses_files','category'=>'categories_photos'];
        $types=['image'=>'photos','file'=>'files','sound'=>'sounds','video'=>'videos'];
        $parent_id=null;
        $model=null;
        foreach ($request->photos_id as $id){
            if ($file=Upload::find($id)){
                $parent_id=$file->parent_id;
                $model=$file->parent;
                $path=$models[$file->parent];
                $type=$types[$file->mimes];
                $fullPath=$path.DIRECTORY_SEPARATOR.$file->parent_id.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$file->path;
                Storage::disk('local')->delete($fullPath);
                $file->delete();
            }else{
                continue;
            }
        }
        toast('Selected Photos Deleted Successfully','success',AlertPosition());
        if ($model=='category'){
            return redirect()->route('category.index');
        }else{
            return redirect()->route($model.'.show',$parent_id);
        }


    }

}
