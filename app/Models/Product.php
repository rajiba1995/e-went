<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = "products";
    protected $fillable = [
        'title',
        'product_sku',
        'types',
        'short_desc',
        'long_desc',
        'category_id',
        'sub_category_id',
        'image',
        'status',
        'is_featured',
        'is_new_arrival',
        'is_bestseller',
        'meta_title',
        'meta_description',
        'is_driving_licence_required',
        'meta_keyword',
        'is_selling',
        'is_rent',
        'base_price',
        'display_price',
    ];

    public function getRentDurationAttribute()
    {
        return env('DEFAULT_RENT_DURATION', 30);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }
    public function rentalprice()
    {
        return $this->hasMany(RentalPrice::class)->orderBy('duration', 'ASC')->where('status',1);
    }
    public function stock_item(){
        return $this->hasMany(Stock::class, 'product_id', 'id');
    }
    public function payment_item(){
        return $this->hasMany(PaymentItem::class, 'product_id', 'id');
    }
    public function ProductImages()
    {
        return $this->hasMany(ProductImage::class);
    }
    
    // public function types()
    // {
    //     return $this->belongsToMany(ProductType::class);
    // }
}
