<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [];

    public function reciever()
    {
      $this->belongsTo('App\User','to_user','id');
    }
    public function sender()
    {
      $this->belongsTo('App\User','from_user','id');
    }
}
