<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $table='instructors';
    protected $fillable=['name','title','phone','email','photo'];

    public function courses(){
        return $this->hasMany('App\Course');
    }
    public function articles(){
        return $this->hasMany('App\Article');
    }
    public function posts(){
        return $this->hasMany('App\Post');
    }

}
