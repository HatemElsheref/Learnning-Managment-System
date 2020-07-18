<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RateLesson extends Model
{
    protected $table="rate_lessons";
    protected $fillable=['lesson_id','user_id','status','title','content','rate'];
    public function lesson(){
        return $this->belongsTo('App\Lesson','lesson_id','id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
}
