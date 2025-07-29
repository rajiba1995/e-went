<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItemReturn extends Model
{
    use HasFactory;
    protected $fillable = [
         'order_item_id', 'user_id', 'return_date', 'return_status', 'return_condition', 'refund_amount','actual_amount',
         'refund_category', 'refund_initiated_by', 'status', 'reason','refund_initiated_at',
         'damaged_part_image','over_due_days','over_due_amnt','port_charges','transaction_id','txnStatus','early_return_days','early_return_amount',
    ];
    public function order_item(){
        return $this->belongsTo(Order::class,'order_item_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id', 'id');
    }
    public function initiated_by(){
        return $this->belongsTo(Admin::class,'refund_initiated_by', 'id');
    }
    public function damageParts(){
        return $this->hasMany(DamagedPartLog::class,'order_item_id', 'id');
    }
}
