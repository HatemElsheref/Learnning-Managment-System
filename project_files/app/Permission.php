<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Permission extends Model
{
    protected $table='permissions';
    protected $fillable=[
        'name','alias'
    ];
    public function admins(){
        return $this->belongsToMany(Admin::class,'dashboard_permission','permission_id','admin_id');
    }
    /* old and slow i replaced it with cache
    public static function checkDashboardPermission($permission){
        $IDSCOLLECTION=DB::table('dashboard_permission')->select('permission_id')->where('admin_id',auth('webadmin')->id())->get();
        $IDS=$IDSCOLLECTION->pluck('permission_id')->toArray();
        $PERMISSIONSCOLLECTION=DB::table('permissions')->select('name')->whereIn('id',$IDS)->get();
        $PERMISSIONS=$PERMISSIONSCOLLECTION->pluck('name')->toArray();

        return in_array($permission,$PERMISSIONS)?true:false;
    }
    */


}
