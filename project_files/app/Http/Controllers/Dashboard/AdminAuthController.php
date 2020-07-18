<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Mail\ResetAdminPassword;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Admin;
use Carbon\Carbon;
class AdminAuthController extends Controller
{
    use NotificationTrait;
    public function LoginForm(){
        return view('dashboard.auth.login');
    }
    public function Login(Request $request){
        $request->validate([
            'email'=>'required|string|max:191',
            'password'=>'required|string|max:191',
        ]);

        $rememberMe=$request->remember?true:false;


        $admin=\Admin()->attempt(['email'=>$request->email,'password'=>$request->password],$rememberMe);
        if ($admin){
            /* cache the permissions in file*/
            if (checkIfCacheUsed()){
                storeAdminPermissions(auth('webadmin')->user()->id);
            }
            toast('Welcome Back Starting New Session ..','success',AlertPosition());
            return redirect(CurrentLanguage().RouteServiceProvider::Dashboard);
        }
        session()->flash('login_failed','login_failed');
        return redirect()->back()->withInput();
    }
    public function Logout(){
        /* Remove  permissions form cache in file*/  RemoveAdminPermissions();
        \Admin()->logout();

        return back();
    }
    public function ForgetPasswordForm(){
        return view('dashboard.auth.forget');
    }
    public function ForgetPassword(Request $request){
        $request->validate([
           'email'=>'required|email'
        ]);

//        $admin=DB::table('admins')->select('email')->where('email',$request->email)->first();
        $admin=Admin::where('email',$request->email)->first();
        if (!$admin){
              return back()->withInput()->with('email_not_found','email_not_found')->with('type','danger');
        }
        $token=app('auth.password.broker')->createToken($admin);

        $row=DB::table('password_resets')->insert([
            'email'=>$request->email,
            'token'=>$token,
            'created_at'=>Carbon::now()
        ]);
        if (!$row){
            return back()->withInput()->with('email_not_found','unknown_error')->with('type','danger');
        }
        $url=route('ShowResetPasswordForm',$token);

        Mail::to($request->email)->send(new ResetAdminPassword($url));

        return redirect(route('ShowLoginForm'))->with('email_not_found','email_sent_successfully')->with('type','success');
//        return redirect(route('ShowResetPasswordForm',$token))->with('email_not_found','email_sent_successfully')->with('type','success');

    }
    public function ResetPasswordForm($token){

        $check_token=DB::table('password_resets')->where('token',$token)->where('created_at','>',(Carbon::now())->subHours(2))->first();

        if (!empty($check_token)){
            return view('dashboard.auth.reset',compact('check_token'));
        }
    }
    public function ResetPassword(Request $request,$token){
        $request->validate([
            'password'=>'required|confirmed|min:5',
            'password_confirmation'=>'required'
        ]);
        $check_token=DB::table('password_resets')->where('token',$token)->where('created_at','>',(Carbon::now())->subHours(2))->first();
        if (!empty($check_token)){
            $admin=Admin::where('email',$check_token->email)->first();
            $admin->email=$check_token->email;
            $admin->password=bcrypt($request->password);
            $admin->save();
            DB::table('password_resets')->where('email',$check_token->email)->delete();
            auth('webadmin')->loginUsingId($admin->id);
            return redirect(route('ShowLoginForm'));
        }
    }

}

