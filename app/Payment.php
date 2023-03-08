<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  public $timestamps = false;
  protected $guarded = ['id'];



  public function subscription()
  {
    return $this->belongsTo('App\UserSubscription', 'subscription_id', 'id');
  }
}
