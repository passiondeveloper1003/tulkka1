<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    //
    public function getDetailsAttribute(){
        return [
            'id'=>$this->id ,
            'title'=>$this->title 
        ] ;
    }
}
