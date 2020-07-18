<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Admin;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    use NotificationTrait;

    public function __construct() {
        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:read_admins')->only(['index','show']);
        $this->middleware('DashboardPermission:create_admins')->only(['create','store']);
        $this->middleware('DashboardPermission:update_admins')->only(['edit','update']);
        $this->middleware('DashboardPermission:delete_admins')->only(['destroy']);
    }

    public function index()
    {
        $staffs=Admin::where('role','staff')->get();
        return view('dashboard.admin.index',compact('staffs'));
    }

    public function create()
    {
        return view('dashboard.admin.create');
    }

    public function store(Request $request)
    {
        $this->FormValidateForStore($request);
//        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
//        if (!preg_match($regex, $request->email)) {
//            return redirect()->back()->withInput()->withErrors('error_in _email');
//        }
        if (!filter_var($request->email,FILTER_VALIDATE_EMAIL)){
            return redirect()->back()->withInput()->withErrors('error_in _email');
        }
        $request->file('avatar')->storeAs('admins_avatars',$request->file('avatar')->hashName());
        $validated_data=$request->all();
        $validated_data['role']='staff';
        $validated_data['password']=bcrypt($request->password);
        $validated_data['avatar']=$request->file('avatar')->hashName();
        $admin=Admin::create($validated_data);
        if ($admin){
            $admin->permissions()->attach(PermissionsIDs($request->permissions));
//            $this->Notify('success_operation_msg','success_operation_title','success');
            toast('Staff Added Successfully','success',AlertPosition());
            return redirect(route('admin.index'));
        }
    }

    public function show($id)
    {
        $staff=Admin::with('permissions')->find($id);
        if ($staff) {
            if (($staff->role=='admin' and auth('webadmin')->user()->role=='admin') or $staff->role != 'admin') {
                return view('dashboard.admin.profile',compact('staff'));
            } else{
                toast('Not Allowed To You To Be Here','error',AlertPosition());
                return redirect()->route('admin.index');
            }
        }
        toast('Not Found , Determine Correct Staff Id','error',AlertPosition());
        return redirect()->route('admin.index');

    }

    public function account($id)
    {
        $staff=Admin::with('permissions')->find($id);
        if ($staff) {
            if (($staff->role=='admin' and auth('webadmin')->user()->role=='admin') or $staff->role != 'admin') {
                return view('dashboard.admin.profile',compact('staff'));
            } else{
                toast('Not Allowed To You To Be Here','error',AlertPosition());
                return redirect()->route('admin.index');
            }
        }
        toast('Not Found , Determine Correct Staff Id','error',AlertPosition());
        return redirect()->route('admin.index');

    }

    public function edit($id)
    {
        $staff=Admin::with('permissions')->find($id);
        if ($staff){
            if ($staff->role =='admin'){
                toast('Not Allowed To You To Be Here','error',AlertPosition());
                return    redirect()->route('admin.index');
            }
            return view('dashboard.admin.edit',compact('staff'))->with('permissions',$staff->permissions->pluck('name')->toArray());
        }
        toast('Not Found , Determine Correct Staff Id','error',AlertPosition());
            return    redirect()->route('admin.index');
    }

    public function update(Request $request, $id)
    {
        //for staff only
        $staff=Admin::find($id);
        if ($staff){
            if ($staff->role !='admin'){
                $this->FormValidateForUpdate($request,$id);
//                $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
//                if (!preg_match($regex, $request->email)) {
//                    return redirect()->back()->withInput()->withErrors('error_in _email');
//                }
                if (!filter_var($request->email,FILTER_VALIDATE_EMAIL)){
                    return redirect()->back()->withInput()->withErrors('error_in _email');
                }
                $validated_data=$request->all();
                if ($request->hasFile('avatar') and $request->file('avatar') !=null){
                             //delete old and store new
                    Storage::disk('local')->delete('admins_avatars'.DIRECTORY_SEPARATOR.$staff->avatar);
                    $request->file('avatar')->storeAs('admins_avatars',$request->file('avatar')->hashName());
                    $validated_data['avatar']=$request->file('avatar')->hashName();
                } else{
                    $validated_data['avatar']=$staff->avatar;
                }
                if ($request->has('password')){
                    $validated_data['password']=bcrypt($request->password);
                }   else{
                    $validated_data['password']=$staff->password;
                }
                $staff->update($validated_data);
                $staff->permissions()->sync(PermissionsIDs($request->permissions));
                (auth('webadmin')->user()->id==$id)?auth('webadmin')->loginUsingId($id):null;
                if (checkIfCacheUsed()){
                    UpdateAdminPermissions($id);
                }
                toast('Staff Updated Successfully','success',AlertPosition());
                return    redirect()->route('admin.index');
        }
            toast('Not Allowed To You To Be Here','error',AlertPosition());
            return    redirect()->route('admin.index');
        }
        toast('Not Found , Determine Correct Staff Id','error',AlertPosition());
        return    redirect()->route('admin.index');
    }

    public function destroy($id)
    {
        $staff=Admin::find($id);
        if ($staff){
            Storage::disk('local')->delete('admins_avatars'.DIRECTORY_SEPARATOR.$staff->avatar);
            $staff->permissions()->detach();
            if (checkIfCacheUsed()){
                if (Cache::has('Auth_Admin_Permissions-'.$staff->id)){
                    Cache::forget('Auth_Admin_Permissions-'.$staff->id);
                }
            }
            $staff->delete();
            toast('Staff Deleted Successfully','success',AlertPosition());
            return redirect()->route('admin.index');
        }

        toast('Not Found , Determine Correct Staff Id','error',AlertPosition());
        return    redirect()->route('admin.index');
    }

    public function updateAccount(Request $request,$id){
        $staff=Admin::find($id);
        if ($staff){
                if (auth('webadmin')->user()->id==$staff->id){
                            $this->FormValidateForProfile($request,$id);
//                                $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
//                                if (!preg_match($regex, $request->email)) {
//                                    return redirect()->back()->withInput()->withErrors('error_in _email');
//                                }
                    if (!filter_var($request->email,FILTER_VALIDATE_EMAIL)){
                        return redirect()->back()->withInput()->withErrors('error_in _email');
                    }
                            $validated_data=$request->all();
                            if ($request->hasFile('avatar') and $request->file('avatar')!=null){
                                //delete old and store new
                                Storage::disk('local')->delete('admins_avatars'.DIRECTORY_SEPARATOR.$staff->avatar);
                                $request->file('avatar')->storeAs('admins_avatars',$request->file('avatar')->hashName());
                                $validated_data['avatar']=$request->file('avatar')->hashName();
                            } else{
                                $validated_data['avatar']=$staff->avatar;
                            }
                            if ($request->has('password') and $request->password!=null){
                                if (!empty($request->password_old) and ($staff->password==bcrypt($request->password_old))){
                                    $validated_data['password']=bcrypt($request->password);
                                }  else{
                                        return redirect()->route('admin.show',$id)->withErrors('password old not correct');
                                }

                            }   else{
                                $validated_data['password']=$staff->password;
                            }
                            $staff->update($validated_data);
                            auth('webadmin')->loginUsingId($staff->id);

                    toast('Account Updated Successfully','success',AlertPosition());
                            return    redirect()->route('admin.show.profile',auth('webadmin')->user()->id);
                } else{
                    toast('Not Allowed To You To Be Here','error',AlertPosition());
                    return redirect()->route('admin.index');
                }

        }
        toast('Not Found , Determine Correct Staff Id','error',AlertPosition());
        return    redirect()->route('admin.index');
    }

    public function FormValidateForStore(Request $request){
        $request->validate([
            'name'  =>'required|string|max:191',
            'email'  =>'required|string|max:191|unique:admins,email',
            'phone' =>'required|numeric|min:15|unique:admins,phone',
            'avatar'=>'required|image:png,jpg,jpeg',
            'gender'=>'required|in:male,female',
            'password'=>'required|string|max:191|confirmed',
            'permissions'=>'required|array|min:1'
        ],[
             'name.required'=>'required_name',
             'email.required'=>'required_email',
             'phone.required'=>'required_phone',
             'avatar.required'=>'required_avatar',
             'gender.required'=>'required_gender',
             'password.required'=>'required_password',
             'permissions.required'=>'required_permissions',

            'name.string'=>'string_name',
            'email.string'=>'string_email',
            'phone.numeric'=>'number_phone',
            'password.string'=>'string_password',

            'name.max'=>'max_name',
            'email.max'=>'max_email',
            'phone.min'=>'max_phone',
            'password.max'=>'max_password',

            'email.unique'=>'email_unique',
            'phone.unique'=>'phone_unique',

            'gender.in'=>'gender_in',

            'avatar.image'=>'image_avatar',

            'password.confirmed'=>'password_confirmed',

            'permissions.array'=>'permission_array',
            'permissions.min'=>'permission_min'
        ]);

    }

    public function FormValidateForUpdate(Request $request,$adminId=null){
        $request->validate([
            'name'  =>'required|string|max:191',
            'email'  =>'required|string|max:191|unique:admins,email,'.$adminId,
            'phone' =>'required|numeric|min:15|unique:admins,phone,'.$adminId,
            'avatar'=>'image:png,jpg,jpeg',
            'gender'=>'required|in:male,female',
            'password'=>'max:191|confirmed',
            'permissions'=>'required|array|min:1'
        ],[
             'name.required'=>'required_name',
             'email.required'=>'required_email',
             'phone.required'=>'required_phone',
             'gender.required'=>'required_gender',
             'permissions.required'=>'required_permissions',

            'name.string'=>'string_name',
            'email.string'=>'string_email',
            'phone.numeric'=>'number_phone',

            'name.max'=>'max_name',
            'email.max'=>'max_email',
            'phone.min'=>'max_phone',
            'password.max'=>'max_password',

            'email.unique'=>'email_unique',
            'phone.unique'=>'phone_unique',

            'gender.in'=>'gender_in',

            'avatar.image'=>'image_avatar',

            'password.confirmed'=>'password_confirmed',

            'permissions.array'=>'permission_array',
            'permissions.min'=>'permission_min'
        ]);

    }

    public function FormValidateForProfile(Request $request,$adminId=null){
        $request->validate([
            'name'  =>'required|string|max:191',
            'email'  =>'required|string|max:191|unique:admins,email,'.$adminId,
            'phone' =>'required|numeric|min:15|unique:admins,phone,'.$adminId,
            'avatar'=>'image:png,jpg,jpeg',
            'gender'=>'required|in:male,female',
            'password'=>'max:191|confirmed',
        ],[
             'name.required'=>'required_name',
             'email.required'=>'required_email',
             'phone.required'=>'required_phone',
             'gender.required'=>'required_gender',


            'name.string'=>'string_name',
            'email.string'=>'string_email',
            'phone.numeric'=>'number_phone',

            'name.max'=>'max_name',
            'email.max'=>'max_email',
            'phone.min'=>'max_phone',
            'password.max'=>'max_password',

            'email.unique'=>'email_unique',
            'phone.unique'=>'phone_unique',

            'gender.in'=>'gender_in',

            'avatar.image'=>'image_avatar',

            'password.confirmed'=>'password_confirmed',

        ]);

    }

}
