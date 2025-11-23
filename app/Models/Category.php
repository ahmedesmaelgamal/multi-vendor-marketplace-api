<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded=[];


    ##  Mutators and Accessors
    public function getImageAttribute()
    {
        return get_file($this->attributes['image']);
    }

    ## Scopes
    public function scopeMainCategory($q){
        return $q->where('level',1);
    }

    public function scopeSubCategory($q){
        return $q->where('level',2);
    }

    ## Relations
    public function subCategory(){
        return $this->hasMany(CategorySubCategories::class,'category_id');
    }
    public function products(){
        return $this->hasMany(Product::class,'sub_category_id');
    }

    //  public function categories()
    // {
    //     return $this->hasMany(related: ProviderCategories::class);
    // }

    public function providers(){
        return $this->hasMany(ProviderCategories::class);
    }

}//end class
