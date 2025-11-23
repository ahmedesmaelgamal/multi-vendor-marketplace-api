<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryTime extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function orderOffers(){
        return $this->hasMany(OrderOffer::class);
    }

}//end class
