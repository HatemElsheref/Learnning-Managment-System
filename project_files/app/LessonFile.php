<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonFile extends Model
{
    protected  $table='lesson_files';
    protected $fillable=['name','type','path','lesson_id','isFree','hosting'];

    public function lesson(){
        return $this->belongsTo('App\Lesson','lesson_id','id');
    }
}
