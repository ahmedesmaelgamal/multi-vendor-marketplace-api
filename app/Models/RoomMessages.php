<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomMessages extends Model
{
    use HasFactory;
    protected $guarded= [];
    protected $appends=['user','provider'];
    ##  Mutators and Accessors
    public function getFileAttribute()
    {
        return get_file($this->attributes['file']);
    }//end fun

    public function getProviderAttribute()
    {
        return Provider::find($this->provider_id);
    }//end fun

    public function getUserAttribute()
    {
        return User::find($this->user_id);
    }//end fun
}//end fun
