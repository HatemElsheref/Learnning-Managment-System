<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    CONST _PATH='users';

    use NotificationTrait;

    public function __construct() {

        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:read_users')->only(['index']);
        $this->middleware('DashboardPermission:update_users')->only(['update']);
        $this->middleware('DashboardPermission:delete_users')->only(['destroy','MultiDelete']);
    }

    public function index()
    {
        $users=User::with('courses','department')->get();
        return view('dashboard.users.index',compact('users'));
    }

    public function update($id)
    {
        $student=User::find($id);
        if (!$student){
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect(route('students.index'));
        }
        if ($student->isBlocked){
                $student->isBlocked=false;
                $student->save();
                $msg='Student '.$student->name.' Un Blocked Successfully';
        }else{
                $student->isBlocked=true;
                $student->save();
            $msg='Student '.$student->name.' Blocked Successfully';
        }
        toast($msg,'success',AlertPosition());
        return  redirect(route('students.index'));
    }

    public function destroy($id)
    {
        //remove student will remove  courses  he paid  it

        $student=User::find($id);
        if (!$student){
            toast('Not Found , Determine Correct Id','error',AlertPosition());
            return  redirect(route('students.index'));
        }else{
            DB::table('course_user')
                ->where('user_id','=',$student->id)->delete();

            if ($student->photo != 'default.png'){
                Storage::disk('local')->delete(self::_PATH.DS.$student->photo);
            }
            $student->delete();
            toast('Student Deleted Successfully','success',AlertPosition());
            return redirect()->route('students.index');
        }
    }

    public function MultiDelete(Request $request)
    {
        $request->validate([
            'students_id'=>'required|array|min:1'
        ]) ;

        foreach ($request->students_id as $id){
            $student=User::find($id);
            if ($student){
                DB::table('course_user')
                    ->where('user_id','=',$student->id)->delete();
                if ($student->photo != 'default.png'){
                    Storage::disk('local')->delete(self::_PATH.DS.$student->photo);
                }
                $student->delete();
            }else{
                continue;
            }
        }
        toast('Selected Students Deleted Successfully','success',AlertPosition());
        return redirect()->route('students.index');
    }

}
