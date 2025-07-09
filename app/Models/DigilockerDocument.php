<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigilockerDocument extends Model
{
    protected $table = "digilocker_documents";
    protected $fillable = [
        'user_id', 'request_id', 'webhook_security_key', 'request_timestamp', 'sdk_url', 'success', 'response_code', 'response_message', 'billable', 'document_type', 'document_name', 'document_status', 'fetched_at', 'issuer', 'issuer_id', 'issue_date', 'document_uri', 'mime_types', 'raw_xml', 'kyc_code', 'kyc_response_status', 'kyc_timestamp'
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
