<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItemReturn extends Model
{
    use HasFactory;
    protected $fillable = [
         'order_item_id', 'user_id', 'return_date', 'return_status', 'return_condition', 'refund_amount', 'refund_category', 'refund_initiated_by', 'status', 'reason','refund_initiated_at',
    ];
    public function order_item(){
        return $this->belongsTo(Order::class,'order_item_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id', 'id');
    }
    public function initiated_by(){
        return $this->belongsTo(Admin::class,'refund_initiated_by', 'id');
    }
}
