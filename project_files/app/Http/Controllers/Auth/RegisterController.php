<?php

namespace App\Http\Controllers\Auth;

use App\Department;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\University;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'regex:/(.+)@(.+)\.(.+)/i','unique:users,email'],
            'phone' => ['required', 'numeric', 'unique:users,phone'],
            'country' => ['required', 'string','max:300'],
            'address' => ['required', 'string','max:255'],
            'department_id' => ['required', 'numeric', 'exists:departments,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'photo' => 'default.png',
            'address' => $data['address'],
            'country' => $data['country'],
            'department_id' => $data['department_id'],
            'password' => Hash::make($data['password']),
        ]);
    }
    public function showRegistrationForm(){
        $data=[];
        $data['countries']= Cache::rememberForever('countries',function (){
                return    DB::table('countries')->select(['id','Name'])->get();
            });
        $data['universities']=University::all();
        return view('frontend.auth.register',$data);
    }
    public function getDepartments(Request $request){

        if ($request->ajax()){
            $departments=Department::where('university_id','=',$request->university_id)->get();

            return response()->json(['departments'=>$departments],200);
        }  else{
            return response()->json([],200);
        }
    }
}
