<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public $table = 'courses';
    protected $fillable = [
        'title','description','cost', 'duration'
    ];
    public function user(){
        return $this->belongsToMany('App\User')
        ->withTimestamps();
    }

}
