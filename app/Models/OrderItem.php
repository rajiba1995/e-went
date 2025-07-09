<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable =[
        'order_id', 'product_id', 'quantity', 'price', 'total_price'  
    ];

    public function product(){
        return $this->belongsTo(Product::class,'product_id', 'id');
    }
    public function order(){
        return $this->belongsTo(Order::class,'order_id', 'id');
    }
}
