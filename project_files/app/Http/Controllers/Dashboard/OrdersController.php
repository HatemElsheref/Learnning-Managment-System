<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Course;


class OrdersController extends Controller
{
    public function __construct() {

        $this->middleware('AdminAuth:webadmin');
        $this->middleware('DashboardPermission:read_course_orders')->only('index');
        $this->middleware('DashboardPermission:update_course_orders')->only('approve');
        $this->middleware('DashboardPermission:delete_course_orders')->only(['destroy','MultiDelete']);
    }


    public function index(){
        $data=['users.name','users.address','users.email','users.phone','courses.name as course','status','course_user.id'];
        $orders=DB::table('course_user')->select($data)
//            ->where('status','=','closed')
            ->join('users','user_id','=','users.id')
            ->join('courses','course_id','=','courses.id')->orderByDesc('id')->get();
//        dd($orders);
          return view('dashboard.orders',compact('orders'));
    }

    public function approve($id){
        $order=DB::table('course_user')->find($id);
            if ($order){
                if ($order->status=='closed')   {
                    DB::table('course_user')->where('id','=',$id)->update(['status'=>'opened']);
                    toast('Course Order Approved Successfully','success',AlertPosition());
                }   else{
                    DB::table('course_user')->where('id','=',$id)->update(['status'=>'closed']);
                    toast('Course Order Canceled Successfully','success',AlertPosition());
                }
                Cache::forget('orders');
                Cache::forget('recent_courses');
                Cache::forget('total_courses');
                  removeCache('orders');
                return redirect()->route('orders.index');
            }    else{
                toast('Course Order Not Found','error',AlertPosition());
                return redirect()->route('orders.index');
            }
    }

    public function destroy($id){
        $order=DB::table('course_user')->find($id);
        if ($order){
            $course=Course::with('parts','parts.lessons')->find($order->course_id);
            $lessons_ids=[];
            foreach ($course->parts as $part){
                     foreach ($part->lessons as $lesson){
                         array_push($lessons_ids,$lesson->id);
                     }
            }
            Cache::forget('orders');
            Cache::forget('recent_courses');
            Cache::forget('total_courses');
            removeCache('orders');
            DB::table('rate_courses')->where('user_id','=',$order->user_id)->where('course_id','=',$order->course_id)->delete();
            DB::table('rate_lessons')->where('user_id','=',$order->user_id)->whereIn('lesson_id',$lessons_ids)->delete();
            DB::table('course_user')->where('id','=',$id)->delete();
            toast('Course Order Deleted Successfully','success',AlertPosition());
            return redirect()->route('orders.index');
        }    else{
            toast('Course Order Not Found','error',AlertPosition());
            return redirect()->route('orders.index');
        }
    }

    public function multiDelete(Request $request){
        $request->validate([
            'orders_id'=>'required|array|min:1'
        ]);
        foreach ($request->orders_id as $id){
            $order=DB::table('course_user')->find($id);
            if ($order){
                Cache::forget('orders');
                Cache::forget('recent_courses');
                Cache::forget('total_courses');
                removeCache('orders');
                DB::table('course_user')->where('id','=',$id)->delete();
            }   else{
                continue;
            }

        }
        toast('Selected Course Orders Deleted Successfully','success',AlertPosition());
        return redirect()->route('orders.index');
    }
}
