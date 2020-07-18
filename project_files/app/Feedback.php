<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Feedback extends Model
{
    protected $table='feedback';
    protected $fillable=[
        'name','country_id','department_id','type','feedback'
    ];
    public function department(){
        return $this->belongsTo('App\Department');
    }

}
