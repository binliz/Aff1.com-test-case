<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    //
    public function timestart(){
        return $this->hasMany(MessageTime::class);
    }

    public function timestartAt($time,$time2){
        return $this->hasMany(MessageTime::class)->whereBetween('start_at',[$time,$time2]);
    }

}
