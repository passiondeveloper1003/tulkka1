<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
  protected $table = 'user_subscription_details';
  public $timestamps = false;
  protected $guarded = [];


  public function user()
  {
    return $this->belongsTo('App\User', 'user_id', 'id');
  }

  public function payment()
  {
    return $this->hasOne('App\Payment', 'subscription_id', 'id');
  }
}
