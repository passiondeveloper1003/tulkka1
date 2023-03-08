<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherDisabledDate extends Model
{
  protected $table = 'teachers_disabled_dates';
  public $timestamps = false;
  protected $guarded = ['id'];

  public function teacher()
  {
    return $this->belongsTo('App\User', 'teacher_id', 'id');
  }
}
