<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $guarded=[];
    protected $appends=['from','to'];

    public function getFromAttribute()
    {
        return date('h:i A',$this->time->from);
    }

    public function getToAttribute()
    {
        return date('h:i A',$this->time->to);
    }

    public function time()
    {
        return $this->belongsTo(DeliveryTime::class,'time_id');
    }

}//end class
