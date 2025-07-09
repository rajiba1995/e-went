<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletTransaction extends Model
{
   use hasFactory;
   protected $table = 'wallet_transactions';
   protected $fillable = [
      'wallet_id', 'order_id', 'transaction_type', 'amount', 'description', 'transaction_date'
   ];
}
