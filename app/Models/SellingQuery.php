<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellingQuery extends Model
{
    protected $table = "selling_queries";
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
