<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RateCourse extends Model
{
    protected $table='rate_courses';

    protected $fillable=['course_id','user_id','status','title','content','rate'];

    public function course(){
        return $this->belongsTo('App\Course','course_id','id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }

}
