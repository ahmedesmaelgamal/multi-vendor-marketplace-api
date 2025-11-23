<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderOfferDetails extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['product', 'new_qty', 'other_product'];

    public function getProductAttribute()
    {
        //        if (in_array($this->type,['less','price'])){
        return Product::with('mainCategory', 'subCategory')->find($this->product_id);
    }
    public function getOtherProductAttribute()
    {
        //        if (in_array($this->type,['less','price'])){
        return Product::with('mainCategory', 'subCategory')->find($this->other_product_id);
    }
    public function getNewQtyAttribute()
    {
        if (in_array($this->type, ['other', 'price'])) {
            $orderDetails = OrderDetails::where('order_id', $this->order_id)
                ->where('product_id', $this->product_id)->first();
            return $orderDetails->qty ?? 0;
        } else {
            return $this->available_qty;
        }
    } //end fun
    /**
     * @return mixed
     */
    public function order_offer()
    {
        return $this->belongsTo(OrderOffer::class, 'order_offer_id');
    } //end fun

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function other_product()
    {
        return $this->belongsTo(Product::class, foreignKey: 'other_product_id');
    }
}//end class
