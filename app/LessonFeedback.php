<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonFeedback extends Model
{
    public $timestamps = false;
    public $table = 'lesson_feedbacks';
    protected $guarded = ['id'];


    public function lesson()
    {
        return $this->belongsTo('App\Lesson', 'lesson_id', 'id');
    }
}
