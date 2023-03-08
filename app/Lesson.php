<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    public $table = 'classes';
    public $timestamps = false;
    protected $guarded =["id"];


    public function teacher()
    {
        return $this->belongsTo('App\User', 'teacher_id', 'id');
    }
    public function student()
    {
        return $this->belongsTo('App\User', 'student_id', 'id');
    }
    public function homeworks()
    {
        return $this->hasMany('App\Homework', 'lesson_id', 'id');
    }

    public function feedback()
    {
        return $this->hasOne('App\LessonFeedback', 'lesson_id', 'id');
    }
}
