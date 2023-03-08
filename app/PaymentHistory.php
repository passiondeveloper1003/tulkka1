<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    public $table = 'payment_history';
    public $guarded = [];
    public $timestamps = false;
}
