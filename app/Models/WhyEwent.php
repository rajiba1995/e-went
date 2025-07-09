<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WhyEwent extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural of the model name
    protected $table = 'why_ewent';

    // Allow mass assignment for these fields
    protected $fillable = ['title', 'image', 'status', 'position'];
}
