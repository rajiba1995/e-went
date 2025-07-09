<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderAddress extends Model
{
    use HasFactory;
    protected $table = 'order_addresses';
    protected $fillable = [
        'order_id', 'user_address_id','created_at', 'updated_at'
    ];

    public function address(){
        return belongsTo(UserAddress::class,'user_address_id','id');
    }
}
