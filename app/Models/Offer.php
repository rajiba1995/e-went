<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'offers';
    protected $fillable = [
        'coupon_code', 'discount_type', 'discount_value', 'minimum_order_amount', 'maximum_discount', 'start_date', 'end_date', 'usage_limit', 'usage_per_user', 'status'
    ];
}
