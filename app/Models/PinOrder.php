<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinOrder extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'pin_orders';
}
