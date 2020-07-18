<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table="departments";
    protected $fillable=['name','photo','university_id','slug','meta_title','meta_description','meta_keywords'];

    public function university(){
        return $this->belongsTo('App\University','university_id','id');
    }
    public function courses(){
        return $this->hasMany('App\Course','department_id','id');
    }
    public function feedbacks(){
        return $this->hasMany('App\Feedback');
    }
    public function users(){
        return $this->hasMany('App\User');
    }
}
