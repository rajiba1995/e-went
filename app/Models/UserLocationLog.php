<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLocationLog extends Model
{
    protected $table = "user_location_logs";
    protected $fillable =[
        'user_id','latitude','longitude'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
   }
}
