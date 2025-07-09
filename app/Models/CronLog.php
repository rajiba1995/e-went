<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronLog extends Model
{
   protected $table = 'cron_logs';
   protected $fillable = [
        'job_name', 'url', 'request_payload', 'response', 'success', 'error_message', 'executed_at', 'created_at', 'updated_at'
   ];
}
