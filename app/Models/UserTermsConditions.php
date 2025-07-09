<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTermsConditions extends Model
{
    protected $table = 'user_terms_conditions';
    protected $fillable = [ 'mobile', 'request_id', 'group_id', 'email', 'status', 'request_timestamp', 'response_timestamp', 'signer_name', 'signer_city', 'signer_state', 'signer_postal_code', 'signed_at', 'signed_url', 'response_payload'];
}
