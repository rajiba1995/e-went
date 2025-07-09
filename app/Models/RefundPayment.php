<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundPayment extends Model
{
    protected $table = "refund_payments";
    protected $fillable = [
        'user_id','order_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
