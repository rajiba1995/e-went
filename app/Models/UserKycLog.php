<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserKycLog extends Model
{
    protected $table = "user_kyc_logs";
    protected $fillable = [
       'user_id', 'document_type', 'status', 'remarks', 'created_by', 'message','created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
