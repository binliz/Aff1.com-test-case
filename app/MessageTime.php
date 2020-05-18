<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;
class MessageTime extends Pivot
{
    //
    protected $casts =[
        'start_at'=>'time:H:i'
    ];
    public function getStartAttribute(){
        return Carbon::parse($this->start_at,config('defaults.utc'))->setTimezone('UTC')->timestamp;
    }
}
