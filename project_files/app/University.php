<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $table="universities";
    protected  $fillable=[
        'name','address','photo','description','slug','meta_title','meta_description','meta_keywords'
    ];
    public function departments(){
        return $this->hasMany('App\Department','university_id','id');
    }
}
