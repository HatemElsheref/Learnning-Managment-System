<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table='lessons';
    protected $fillable=['name','part_id','video','status','type'];

//    public function course(){
//        return $this->belongsTo('App\Course');
//    }
    public function part(){
        return $this->belongsTo('App\Part','part_id','id');
    }
    public function files(){
        return $this->hasMany('App\LessonFile','lesson_id');
    }
    public function isPublished(){
        return $this->status==true;
    }
    public function rates(){
        return $this->hasMany('App\RateLesson');
    }
}
