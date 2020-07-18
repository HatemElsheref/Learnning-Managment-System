<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaidCourses extends Model
{
    protected $table='paid_courses';
    protected $fillable=['user_id','course_id','status'];


}
