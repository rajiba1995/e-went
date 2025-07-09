<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    protected $table = "product_features";

    protected $fillable = [
        'product_id',
        'title'
    ];

}
