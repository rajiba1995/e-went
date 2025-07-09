<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsignedVehicle extends Model
{
    use HasFactory;
    protected $table = "assigned_vehicles";
    protected $fillable = [
        'user_id', 'order_id', 'vehicle_id', 'start_date', 'end_date', 'status', 'assigned_at', 'assigned_by',
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function stock(){
        return $this->belongsTo(Stock::class,'vehicle_id','id');
    }
    public function order(){
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class,'assigned_by','id');
    }
}
