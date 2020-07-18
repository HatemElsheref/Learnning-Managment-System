<?php

namespace App\Http\Controllers\Dashboard;


use App\Http\Controllers\Controller;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function __construct() {

        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:read_project')->only(['index']);
        $this->middleware('DashboardPermission:read_photos')->only(['show']);
        $this->middleware('DashboardPermission:create_project')->only(['create','store']);
        $this->middleware('DashboardPermission:update_project')->only(['edit','update']);
        $this->middleware('DashboardPermission:delete_project')->only(['destroy','MultiDelete']);
    }

    public function index(){
        $projects=Project::orderBy('created_at','desc')->get();
        return view('dashboard.projects.index',compact('projects'));
    }

    public function show($id){
        $project=Project::find($id);
        if ($project){
            $photos=DB::table('uploads')->select('*')
                ->where('parent','=','project')
                ->where('parent_id','=',$project->id)
                ->where('mimes','=','image')->get();
            return view('dashboard.projects.show')
                ->with('project',$project)
                ->with('photos',$photos);
        }
        else{
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return redirect()->route('project.index');
        }
    }

    public function create(){
        return view('dashboard.projects.create');
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|max:191',
            'description'=>'required|string',
            'photo'=>'required|image|mimes:jpg,png,jpeg,webp',
            'link'=>'required|url|max:191',
            'type'=>'required|string|max:191'
        ]);
        $validated_data=$request->except(['photo','status']);
        if ($request->has('status') and $request->status=='published'){
            $validated_data['status']=true;
        }else{
            $validated_data['status']=false;
        }
          $validated_data['photo']=$request->file('photo')->hashName();
        $project=Project::create($validated_data);
        Cache::forget('projects');
        removeCache('project');
        if ($project){
            $request->file('photo')->storeAs('projects_photos'.DIRECTORY_SEPARATOR.$project->id,$request->file('photo')->hashName());
            toast('Project Add Successfully','success',AlertPosition());
            return redirect()->route('project.index');
        }
        toast('Failed To Add Project','error',AlertPosition());
        return redirect()->route('project.index');

    }

    public function edit($id){
        $project=Project::find($id);
        if ($project){
            return view('dashboard.projects.edit',compact('project'));
        }else{
                  toast('Project Not Found','error',AlertPosition());
                  return redirect()->route('project.index');
        }

    }

    public function update(Request $request,$id){
        $request->validate([
            'name'=>'required|string|max:191',
            'description'=>'required|string',
            'photo'=>'image|mimes:jpg,png,jpeg,webp',
            'link'=>'required|url|max:191',
            'type'=>'required|string|max:191'
        ]);
        $project=Project::find($id);
        if (!$project){
             toast('Project Not Found','error',AlertPosition());
             return redirect()->route('project.index');
        }
        $validated_data=$request->except(['photo','status']);
        if ($request->has('status') and $request->status=='published'){
            $validated_data['status']=true;
        }else{
            $validated_data['status']=false;
        }
        if ($request->hasFile('photo') and  ! empty($request->file('photo'))){
            Storage::disk('local')->delete('projects_photos'.DIRECTORY_SEPARATOR.$project->id.DIRECTORY_SEPARATOR.$project->photo);
            $request->file('photo')->storeAs('projects_photos'.DIRECTORY_SEPARATOR.$project->id,$request->file('photo')->hashName());
            $validated_data['photo']=$request->file('photo')->hashName();
        }else{
            $validated_data['photo']=$project->photo;
        }
        if ($project->update($validated_data)){
            Cache::forget('projects');
            removeCache('project');
            toast('Project Updated Successfully','success',AlertPosition());
            return redirect()->route('project.index');
        }
        toast('Failed To Update Project','error',AlertPosition());
        return redirect()->route('project.index');
    }

    public function destroy($id){
        $project=Project::find($id);
        if (!$project){
            toast('Project Not Found','error',AlertPosition());
            return redirect()->route('project.index');
        }else{
            File::deleteDirectory(storage_path('app'.DIRECTORY_SEPARATOR.'projects_photos'.DIRECTORY_SEPARATOR.$project->id));
         DB::table('uploads')->select('*')
                ->where('parent','=','project')
                ->where('parent_id','=',$project->id)
                ->where('mimes','=','image')->delete();
         $project->delete();
            Cache::forget('projects');
            removeCache('project');
            toast('Project Deleted Successfully','success',AlertPosition());
            return redirect()->route('project.index');
        }
    }

    public function multiDelete(Request $request){
             $request->validate([
                'projects_id'=>'required|array|min:1'
             ]);
             foreach ($request->projects_id as $id){
                 $project=Project::find($id);
                 if (!$project){
                     continue;
                 }else{
                     File::deleteDirectory(storage_path('app'.DIRECTORY_SEPARATOR.'projects_photos'.DIRECTORY_SEPARATOR.$project->id));
                     DB::table('uploads')->select('*')
                         ->where('parent','=','project')
                         ->where('parent_id','=',$project->id)
                         ->where('mimes','=','image')->delete();
                     $project->delete();
                 }
             }
        Cache::forget('projects');
        removeCache('project');
        toast('Selected Projects Deleted Successfully','success',AlertPosition());
        return redirect()->route('project.index');
    }
}
