<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Faq extends Model
{
    protected $table = 'faqs';

    // Allow mass assignment for these fields
    protected $fillable = ['question', 'answer'];
    public $timestamps = true; // Ensure timestamps are enabled
}
