<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExchangeVehicle extends Model
{
    use HasFactory;
    protected $table = "exchange_vehicles";
    protected $fillable = [
        'user_id', 'order_id', 'vehicle_id', 'start_date', 'end_date', 'status', 'exchanged_at', 'exchanged_by',
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function admin(){
        return $this->belongsTo(Admin::class,'exchanged_by','id');
    }
    public function stock(){
        return $this->belongsTo(Stock::class,'vehicle_id','id');
    }
    public function order(){
        return $this->belongsTo(Order::class,'order_id','id');
    }
}
