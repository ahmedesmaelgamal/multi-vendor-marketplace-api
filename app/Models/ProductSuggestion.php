<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSuggestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    ##  Mutators and Accessors
    public function getImagesAttribute()
    {
        return get_file($this->attributes['images']);
    }



    ##### Relations
    public function provider(){
        return $this->belongsTo(Provider::class,'provider_id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'main_category_id');
    }
}
