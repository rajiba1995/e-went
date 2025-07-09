<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleTimeline extends Model
{
   protected $table = 'vehicle_timelines';

   protected $fillable = [
        'stock_id', 'field', 'value', 'unit', 'created_at', 'updated_at'
   ];
   
   public function stock(){
        return $this->belongsTo(Stock::class, 'stock_id', 'id');
   }
}
