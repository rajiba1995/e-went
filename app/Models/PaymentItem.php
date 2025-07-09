<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentItem extends Model
{
    use HasFactory;
    protected $table = 'payment_items';
    protected $fillable = [
            'payment_for', 'payment_id', 'product_id', 'vehicle_id', 'duration', 'type', 'amount'
    ];

    public function payment(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function stock(){
        return $this->belongsTo(Stock::class, 'vehicle_id', 'id');
    }
}
