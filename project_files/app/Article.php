<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    protected  $table='articles';
    protected $fillable=[
        'title','subtitle','photo','content','dir','tags','course_id','instructor_id','slug','meta_title','meta_description','meta_keywords'
    ];
    public function course(){
        return $this->belongsTo('App\Course');
    }
    public function author(){
        return $this->belongsTo('App\Instructor');
    }
    public function instructor(){
        return $this->belongsTo('App\Instructor');
    }
}
