<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table='posts';
    protected $fillable=[
        'title','description','content','dir','photo','status','instructor_id','category_id','slug','meta_title','meta_description','meta_keywords'
    ];
    public function tags(){
        return $this->belongsToMany('App\Tag');
    }
    public function category(){
        return $this->belongsTo('App\Category');
    }
    public function instructor(){
        return $this->belongsTo('App\Instructor');
    }
}
