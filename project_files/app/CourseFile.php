<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseFile extends Model
{
    protected $table='course_files';
    protected $fillable=[
        'path','course_id','type','year','term','hosting','status','shared'
    ];
    public function course(){
        return $this->belongsTo('App\Course','course_id','id');
    }
}
