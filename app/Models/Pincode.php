<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pincode extends Model
{
    use HasFactory;

    protected $table = 'pincodes';

    protected $fillable = [
        'pincode', 'city_id', 'status'
    ];
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
