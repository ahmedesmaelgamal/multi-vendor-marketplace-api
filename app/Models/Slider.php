<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $guarded=[];

    ##  Mutators and Accessors
    public function getImageAttribute()
    {
        return get_file($this->attributes['image']);
    }

    ## Scopes
    public function scopeActive($q){
        return $q->where('end_at','>=',Carbon::now());
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}//end class
