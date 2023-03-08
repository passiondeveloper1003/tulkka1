<?php

namespace App\Models\Api;

use App\Models\Payout as Model;

class Payout extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\Api\User', 'user_id', 'id');
    }

    public function getDetailsAttribute(){
        return [

            'id'=>$this->id ,
          //  'user'=>$this->user->brief ,
            'amount'=>$this->amount ,
            'account_name'=>$this->account_name ,
            'account_number'=>$this->account_number ,
            'account_bank_name'=>$this->account_bank_name ,
            'status'=>$this->status ,
            'created_at'=>$this->created_at 


        ] ;
    }
}
