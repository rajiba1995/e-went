<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancelRequestHistory extends Model
{
   protected $table = "cancel_request_histories";

   protected $fillable = [
    'order_id', 'user_id', 'request_date', 'vehicle_id', 'accepted_date', 'accepted_by', 'type', 'rejected_reason'
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
        return $this->belongsTo(Admin::class,'accepted_by','id');
    }
}
