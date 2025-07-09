<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use SoftDeletes; 

    protected $table = "sub_categories";
    protected $fillable = [
        'category_id','title'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

}
