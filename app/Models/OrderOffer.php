<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderOffer extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function offer_details()
    {
        return $this->hasMany(OrderOfferDetails::class,'order_offer_id');
    }//end fun
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class,'provider_id');
    }

    public function delivery_time(){
        return $this->belongsTo(DeliveryTime::class,'delivery_date_time');
    }


}
