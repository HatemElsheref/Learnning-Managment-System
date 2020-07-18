<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    protected  $table='courses';
    protected $fillable=[
        'name','code','description','photo','video','intro','price','instructor_id','department_id','slug','meta_title','meta_description','meta_keywords'
    ];

    public function instructor(){
        return $this->belongsTo('App\Instructor');
    }
    public function department(){
        return $this->belongsTo('App\Department');
    }
//    public function lessons(){
//        return $this->hasMany('App\Lesson');
//    }
    public function parts(){
        return $this->hasMany('App\Part');
    }
    public function files(){
        return $this->hasMany('App\CourseFile','course_id');
    }
    public function articles(){
        return $this->hasMany('App\Article');
    }

    public function rates(){
        return $this->hasMany('App\RateCourse');
    }
    public function users(){
        return  $this->belongsToMany('App\User');
        }
    public function isFree(){
        return $this->price==0;
    }
}
