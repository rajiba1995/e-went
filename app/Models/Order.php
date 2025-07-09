<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
       'user_id', 'order_type', 'order_number', 'product_id', 'subscription_id', 'deposit_amount', 'rental_amount', 'total_price', 'discount_amount', 'final_amount', 'quantity', 'payment_mode', 'payment_status', 'shipping_address', 'rent_duration', 'rent_start_date', 'rent_end_date', 'return_date', 'rent_status', 'created_at', 'updated_at'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function subscription(){
        return $this->belongsTo(RentalPrice::class, 'subscription_id', 'id');
    }
    public function payments(){
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }
    public function deposit_payment(){
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }

    public function vehicle()
    {
        return $this->hasOne(AsignedVehicle::class);
    }
    public function refund_payment()
    {
        return $this->hasOne(RefundPayment::class);
    }
    public function exchange_vehicle()
    {
        return $this->hasMany(ExchangeVehicle::class);
    }
    // public function offer(){
    //     return $this->belongsTo(Offer::class, 'offer_id', 'id');
    // }
}
