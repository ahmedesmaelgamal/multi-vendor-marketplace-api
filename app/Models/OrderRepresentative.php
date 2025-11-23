<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRepresentative extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }

    public function representative(){
        return $this->belongsTo(Representative::class,'representative_id');
    }




}
