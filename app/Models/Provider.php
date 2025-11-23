<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Provider extends Authenticatable
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = ['rate'];

    ##  Mutators and Accessors
    public function getImageAttribute()
    {
        return get_file($this->attributes['image']);
    }

    public function getRateAttribute()
    {
        $rate = $this->reviews->avg('rate');
        if ($rate)
            return round($rate);
        return 0;
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class, 'provider_id');
    }

    public function review()
    {
        return $this->hasOne(Reviews::class, 'provider_id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'provider_categories',
            'provider_id',
            'category_id'
        )->withPivot('id')->withTimestamps();
    }


    public function commercial_records()
    {
        return $this->hasMany(CommercialRecords::class);
    }


    public function hiddenProducts()
    {
        return $this->hasMany(ProductsDontHaveProvider::class, 'provider_id');
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nationality_id');
    }

    public function town()
    {
        return $this->belongsTo(Town::class, 'town_id');
    }
}//end class
