<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function user(){
        return $this->belongsToMany('App\User', 'course_user')
        ->withTimestamps()->select('name');
    }

}
