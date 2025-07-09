<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItemReturn extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_item_id', 'return_date', 'return_status', 'return_condition', 'refund_amount',
    ];
    public function order_item(){
        return $this->belongsTo(OrderItem::class,'order_item_id', 'id');
    }
}
