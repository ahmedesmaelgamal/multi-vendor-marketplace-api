<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    use HasFactory;
//    protected $guarded=[];

    protected $fillable = [
        'image',
        'title_ar',
        'title_en',
    ];

    ##  Mutators and Accessors
    public function getImageAttribute()
    {
        return get_file($this->attributes['image']);
    }


    ## Relations
    public function towns()
    {
        return $this->hasMany(Town::class, 'nationality_id');
    }
}
