<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    use HasFactory;
    protected $fillable = [
        'nationality_id',
        'title_ar',
        'title_en',
    ];


    ## Relations
    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nationality_id');
    }
}
