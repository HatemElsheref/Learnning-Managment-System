<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected  $table='parts';
    protected $fillable=[
        'name','course_id'
    ];
    public function course(){
        return $this->belongsTo('App\Course','course_id','id');
    }
    public function lessons(){
        return $this->hasMany('App\Lesson');
    }
}

