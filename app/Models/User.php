<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function getImageAttribute()
    {
        return get_client_file($this->attributes['image']);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded=[];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tokens($key = null,$type = null)
    {
        return $this->hasMany(Token::class)->when($type, function ($query) use ($type,$key) {
            return $query->where($key, $type);
        });
    }

    public function addresses()
    {
        return $this->hasMany(Address::class,'user_id');
    }

    public function orders(){
        return $this->hasMany(Order::class,'user_id');
    }
}
