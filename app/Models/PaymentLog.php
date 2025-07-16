<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $table = 'payment_logs';

    protected $fillable = [
        'gateway',
        'transaction_id',
        'merchant_txn_no',
        'response_payload',
        'status',
        'message',
        'type',
    ];
}
