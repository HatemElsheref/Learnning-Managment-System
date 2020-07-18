<?php

namespace App\Http\Controllers\Dashboard;

use App\Feedback;
use App\Http\Controllers\Controller;
use App\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class FeedbackController extends Controller
{

    public function __construct() {
        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:read_feedback')->only('index');
        $this->middleware('DashboardPermission:create_feedback')->only(['create','store']);
        $this->middleware('DashboardPermission:update_feedback')->only(['edit','update']);
        $this->middleware('DashboardPermission:delete_feedback')->only(['destroy','MultiDelete']);
    }
    public function index(){
        $feedbacks=Feedback::with('department','department.university')->orderByDesc('created_at')->get();
        $countries=DB::table('countries')->select(['id','Name'])->get()->toArray();
        return view('dashboard.feedback.index',compact('feedbacks'))
            ->with('countries',$countries);
    }
    public function create(){
        $universities=University::all();
        if (count($universities)==0){
            toast('You Must Create University First','info',AlertPosition());
            return redirect()->route('university.create');
        }
        $countries=DB::table('countries')->get(['id','Name']);
        return view('dashboard.feedback.create',['universities'=>$universities,'countries'=>$countries]);
    }
    public function store(Request $request){

        $request->validate([
            'name'=>'required|string|max:191',
            'country_id'=>'required',
            'department_id'=>'required|exists:departments,id',
            'type'=>'required|in:image,audio,video',
            'feedback'=>'required|mimes:jpg,jpeg,png,mp4,mov,ogg,mp3,mpeg,mpga,wav|max:30000'
        ]);
        $country=DB::table('countries')->select()->where('id',$request->country_id)->first();
        if (!$country){
            toast('Country Not Found','error',AlertPosition());
            return redirect()->route('feedback.create');
        }
        $validated_data=$request->except(['feedback','type']);
        if ($request->type=='image'){
            $request->validate([
                'feedback'=>'required|mimes:jpg,jpeg,png'
            ]);
            $validated_data['type']='image';
            $request->file('feedback')->storeAs('feedback'.DIRECTORY_SEPARATOR.'images',$request->file('feedback')->hashName());
            $validated_data['feedback']=$request->file('feedback')->hashName();
        }elseif($request->type=='audio'){
            $request->validate([
                'feedback'=>'required|mimes:wav,mpga,mpeg,mp3'
            ]);
            $validated_data['type']='audio';
            $name=$request->file('feedback')->hashName();
            $name=explode('.',$name);
            $name=$name[0].'.mp3';
//            $request->file('feedback')->storeAs('feedback'.DIRECTORY_SEPARATOR.'audios',$request->file('feedback')->hashName());
            $request->file('feedback')->storeAs('feedback'.DIRECTORY_SEPARATOR.'audios',$name);
//            $validated_data['feedback']=$request->file('feedback')->hashName();
            $validated_data['feedback']=$name;
        }else{
            $request->validate([
                'feedback'=>'required|mimes:mp4,ogg,opus,wav,mpga,mpeg,mp3'
            ]);
            $audios=['mp3','opus','ogg','wav'];
             $extension=$request->file('feedback')->getClientOriginalExtension();
             if (in_array($extension,$audios)){
                 $name=$request->file('feedback')->hashName();
                 $name=explode('.',$name);
                 $name=$name[0].'.'.$extension;
                 $validated_data['type']='audio';
                 $request->file('feedback')->storeAs('feedback'.DIRECTORY_SEPARATOR.'audios',$name);
                 $validated_data['feedback']=$name;
             }else{
                 $validated_data['type']='video';
                 $request->file('feedback')->storeAs('feedback'.DIRECTORY_SEPARATOR.'videos',$request->file('feedback')->hashName());
                 $validated_data['feedback']=$request->file('feedback')->hashName();
             }



        }

        $feedback=Feedback::create($validated_data);
        Cache::forget('feedbackImages_Videos');
        Cache::forget('feedbackAudios');
        removeCache('feedback');
        if ($feedback){
            toast('Feedback Created Successfully','success',AlertPosition());
            return redirect()->route('feedback.index');
        }   else{
            toast('Failed To Create Feedback','success',AlertPosition());
            return redirect()->route('feedback.index');
        }

    }
    public function edit($id){
        $feedback=Feedback::find($id);
        if (!$feedback){
            toast('Feedback Not Found','error',AlertPosition());
            return redirect()->route('feedback.index');
        }
        $universities=University::all();
        $countries=DB::table('countries')->get(['id','Name']);
        return view('dashboard.feedback.edit',['universities'=>$universities,'countries'=>$countries,'feedback'=>$feedback]);
    }
    public function Update(Request $request,$id){

        $request->validate([
            'name'=>'required|string|max:191',
            'country_id'=>'required',
            'department_id'=>'required|exists:departments,id',
            'type'=>'required|in:image,audio,video',
            'feedback'=>'mimes:jpg,jpeg,png,mp4,mov,ogg,mp3,mpeg,mpga,wav'
        ]);

        $feedback=Feedback::find($id);
        if (!$feedback){
            toast('Feedback Not Found','error',AlertPosition());
            return redirect()->route('feedback.index');
        }

        $country=DB::table('countries')->select()->where('id',$request->country_id)->first();
        if (!$country){
            toast('Country Not Found','error',AlertPosition());
            return redirect()->route('feedback.create');
        }
        $validated_data=$request->except(['feedback','type']);
        if ($request->type=='image'){
            $request->validate([
                'feedback'=>'mimes:jpg,jpeg,png'
            ]);
            $validated_data['type']='image';
            if ($request->hasFile('feedback') and ! empty($request->file('feedback'))){
                Storage::disk('local')->delete('feedback'.DIRECTORY_SEPARATOR.$this->getPath($feedback->type).DIRECTORY_SEPARATOR.$feedback->feedback);
                $request->file('feedback')->storeAs('feedback'.DIRECTORY_SEPARATOR.'images',$request->file('feedback')->hashName());
                $validated_data['feedback']=$request->file('feedback')->hashName();
            }else{
                $validated_data['feedback']=$feedback->feedback;
            }
        }elseif($request->type=='audio'){
            $request->validate([
                'feedback'=>'mimes:wav,mpga,mpeg,mp3'

            ]);
            if ($request->hasFile('feedback') and  !empty($request->file('feedback'))){
                $validated_data['type']='audio';
                $name=$request->file('feedback')->hashName();
                $name=explode('.',$name);
                $name=$name[0].'.mp3';
            Storage::disk('local')->delete('feedback'.DIRECTORY_SEPARATOR.$this->getPath($feedback->type).DIRECTORY_SEPARATOR.$feedback->feedback);
            $request->file('feedback')->storeAs('feedback'.DIRECTORY_SEPARATOR.'audios',$name);
            $validated_data['feedback']=$name;
        }else{
                $validated_data['feedback']=$feedback->feedback;
            }
        }else{
            $request->validate([
//                'feedback'=>'mimes:mp4,ogg,ogg'
                'feedback'=>'mimes:mp4,ogg,opus,wav,mpga,mpeg,mp3'

            ]);
            $audios=['mp3','opus','ogg','wav'];
            if ($request->hasFile('feedback') and  !empty($request->file('feedback'))){
                $extension=$request->file('feedback')->getClientOriginalExtension();
                if (in_array($extension,$audios)){
                    $name=$request->file('feedback')->hashName();
                    $name=explode('.',$name);
                    $name=$name[0].'.'.$extension;
                    $validated_data['type']='audio';
                    Storage::disk('local')->delete('feedback'.DIRECTORY_SEPARATOR.$this->getPath($feedback->type).DIRECTORY_SEPARATOR.$feedback->feedback);
                    $request->file('feedback')->storeAs('feedback'.DIRECTORY_SEPARATOR.'audios',$name);
                    $validated_data['feedback']=$name;
                }else{
                    $validated_data['type']='video';
                    Storage::disk('local')->delete('feedback'.DIRECTORY_SEPARATOR.$this->getPath($feedback->type).DIRECTORY_SEPARATOR.$feedback->feedback);
                    $request->file('feedback')->storeAs('feedback'.DIRECTORY_SEPARATOR.'videos',$request->file('feedback')->hashName());
                    $validated_data['feedback']=$request->file('feedback')->hashName();
                }
            }else{
                $validated_data['feedback']=$feedback->feedback;
                $validated_data['type']=$feedback->type;
            }


        }

        if ($feedback->update($validated_data)){
            removeCache('feedback');
            Cache::forget('feedbackImages_Videos');
            Cache::forget('feedbackAudios');
            toast('Feedback Updated  Successfully','success',AlertPosition());
            return redirect()->route('feedback.index');
        }   else{
            toast('Failed To Update Feedback','success',AlertPosition());
            return redirect()->route('feedback.index');
        }

    }
    public function destroy($id){
        $feedback=Feedback::find($id);
        if (!$feedback){
            toast('Feedback Not Found','error',AlertPosition());
            return redirect()->route('feedback.index');
        }
        Storage::disk('local')->delete('feedback'.DIRECTORY_SEPARATOR.$this->getPath($feedback->type).DIRECTORY_SEPARATOR.$feedback->feedback);
        $feedback->delete();
        Cache::forget('feedbackImages_Videos');
        Cache::forget('feedbackAudios');
        removeCache('feedback');
        toast('Feedback Deleted Successfully','success',AlertPosition());
        return redirect()->route('feedback.index');
    }
    public function MultiDelete(Request $request)
    {
        $request->validate([
            'feedback_id'=>'required|array|min:1'
        ]) ;

        foreach ($request->feedback_id as $id){
            $feedback=Feedback::find($id);
            if ($feedback){
                Storage::disk('local')->delete('feedback'.DIRECTORY_SEPARATOR.$this->getPath($feedback->type).DIRECTORY_SEPARATOR.$feedback->feedback);
                $feedback->delete();
            } else{
                continue;
            }

            }
        Cache::forget('feedbackImages_Videos');
        Cache::forget('feedbackAudios');
        removeCache('feedback');
        toast('Selected Feedback Deleted Successfully','success',AlertPosition());
        return redirect()->route('feedback.index');
    }
    public function getPath($type){
        if ($type=='image'){
            return 'images';
        }   elseif($type=='audio'){
            return 'audios';
        }   else{
            return 'videos';
        }
    }

}
