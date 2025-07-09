<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
   use HasFactory;
    protected $table = 'payments';
    protected $fillable = [
      'order_id', 'user_id', 'order_type', 'payment_method', 'payment_status', 'transaction_id', 'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature', 'amount', 'currency', 'payment_date', 'icici_merchantTxnNo','icici_txnID','created_at', 'updated_at'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    public function paymentItem(){
        return $this->hasMany(PaymentItem::class, 'payment_id', 'id');
    }
}
