<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminRating extends Model
{
   use HasFactory;
   protected $table="admin_ratings";
   protected $fillable = [
    'user_id', 'admin_id', 'rating', 'comments',
   ];
}
