<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $guarded= [];
    protected $table='notifications';


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }//end fun
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provider(){
        return $this->belongsTo(Provider::class,'provider_id');
    }//end fun
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }//end fun

    public function representative(){
        return $this->belongsTo(Representative::class,'representative_id');
    }//end fun

}//end class
