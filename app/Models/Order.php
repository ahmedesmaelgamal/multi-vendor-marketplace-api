<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded=[];

    protected $appends=['provider_rated'];

    public function getProviderRatedAttribute()
    {
        $status = false;
        $ifRatedProvider = Reviews::where('user_id',$this->user_id)
            ->where([['provider_id',$this->provider_id],['order_id',$this->id]])->count();

        if($ifRatedProvider)
            $status = true;
        return $status;

    }//end fun




    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(OrderDetails::class,'order_id');
    }//end fun
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offers()
    {
        return $this->hasMany(OrderOffer::class,'order_id')
            ->where('status','!=','rejected');
    }//end fun
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProviders(){
        return $this->hasMany(OrderProviders::class,'order_id');
    }//end fun
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderOffers(){
        return $this->hasMany(OrderOffer::class,'order_id');
    }
    
    //end fun
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address(){
        return $this->belongsTo(Address::class,'address_id');
    }//end fun
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }//end fun
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class,'provider_id');
    }//end fun
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }//end fun
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accepted_offer()
    {
        return $this->belongsTo(OrderOffer::class,'accepted_offer_id');
    }//end fun


//    public function accepted_offer_details(){
//        return $this->hasMany(OrderOfferDetails::class,'accepted_offer_id');
//    }

}//end class
