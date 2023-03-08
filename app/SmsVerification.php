<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsVerification extends Model
{
  protected $table = 'sms_verifications';
  public $timestamps = false;
  public $guarded = [];

  public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
