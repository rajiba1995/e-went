<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural of the model name
    protected $table = 'banners';

    // Allow mass assignment for these fields
    protected $fillable = ['title', 'image', 'status'];

    
}
