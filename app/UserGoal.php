<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGoal extends Model
{
  protected $guarded = ['id'];
  public $timestamps = false;

  public function user()
  {
    return $this->belongsTo('App\User','user_id','id');
  }
}
