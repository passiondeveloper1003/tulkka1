<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class QuizzesQuestion extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = 'quizzes_questions';
    public $timestamps = false;
    protected $guarded = ['id'];

    static $multiple = 'multiple';
    static $descriptive = 'descriptive';

    public $translatedAttributes = ['title', 'correct'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }

    public function getCorrectAttribute()
    {
        return getTranslateAttributeValue($this, 'correct');
    }


    public function quizzesQuestionsAnswers()
    {
        return $this->hasMany('App\Models\QuizzesQuestionsAnswer', 'question_id', 'id');
    }
}
