<?php

namespace App;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Admin extends Authenticatable
{
    use Notifiable;

    protected $table="admins";
    protected $fillable=[
        'name','email','password','role','avatar','phone','gender'
    ];
    protected $hidden=[
        'password','remember_token'
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function permissions(){
        return $this->belongsToMany(Permission::class,'dashboard_permission','admin_id','permission_id');
    }


}
