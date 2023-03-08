<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewQuiz extends Model
{
    public $timestamps = false;
    protected $guarded =["id"];
    public $table = 'quizzes_new';

    public function lesson()
    {
      return $this->belongsTo('App\Lesson', 'lesson_id', 'id');
    }
    public function student()
    {
      return $this->belongsTo('App\User', 'student_id', 'id');
    }
    public function teacher()
    {
      return $this->belongsTo('App\User', 'teacher_id', 'id');
    }
}
