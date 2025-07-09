<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BomPart extends Model
{
   protected $table = "bom_parts";
    protected $fillable = [
        'part_name', 'part_number', 'product_id', 'part_unit', 'part_price', 'warranty_in_day', 'warranty', 'image', 'status'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
