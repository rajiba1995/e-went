<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DamagedPartLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_item_id', 'bom_part_id', 'price', 'log_by'
    ];
    public function order_item(){
        return $this->belongsTo(OrderItem::class,'order_item_id', 'id');
    }
    public function bom_part(){
        return $this->belongsTo(BomPart::class,'bom_part_id', 'id');
    }
}
