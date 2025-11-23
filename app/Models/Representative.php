<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Representative extends Authenticatable
{
    use HasFactory;
    protected $table = 'representatives';
    protected $guarded = [];


    ##  Mutators and Accessors
    public function getImageAttribute()
    {
        return get_representative_image($this->attributes['image']);
    }

    public function nationality(){
        return $this->belongsTo(Nationality::class);
    }
    public function town(){
        return $this->belongsTo(related: Town::class);
    }

    public function provider(){
        return $this->belongsTo(Provider::class);
    }

    public function orderRepresentatives(){
        return $this->hasMany(OrderRepresentative::class);
    }
}
