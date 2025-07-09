<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingActivity extends Model
{
    protected $table = 'shipping_activities';
    protected $fillable = ['order_id', 'status', 'vehicle_id', 'user_id', 'payment_status', 'description', 'vehicle_info', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function vehicle()
    {
        return $this->belongsTo(Stock::class, 'vehicle_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
