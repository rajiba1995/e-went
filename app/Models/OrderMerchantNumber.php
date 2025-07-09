<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMerchantNumber extends Model
{
    protected $table = "order_merchant_numbers";
    protected $fillable = [
    'order_id', 'type','merchantTxnNo', 'redirect_url', 'secureHash', 'tranCtx', 'amount'
    ];
}
