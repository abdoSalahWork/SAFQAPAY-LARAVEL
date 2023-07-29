<?php

namespace App\Models;

use App\Models\setting\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_user_id',
        'profile_business_id',
        'category_id',
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'weight',
        'height',
        'width',
        'length',
        'product_image',
        'quantity',
        'price',
        'currency_id',
        'is_stockable',
        'disable_product_on_sold',
        'is_active',
        'is_shipping_product',
        'in_store'
    ];
    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }
    function currency()
    {
        return $this->belongsTo(Country::class, 'currency_id', 'id');
    }

    public function scopeStockable($query)
    {
        return $query->where('is_stockable', 0)->where('disable_product_on_sold', 0)
            ->orWhere('is_stockable', 1)->where('disable_product_on_sold', 0)
            ->orWhere('is_stockable', 0)->where('disable_product_on_sold', 1)
            ->orWhere('is_stockable', 1)->where('disable_product_on_sold', 1)->where("quantity", ">", 0);
    }
    public function scopeLength($query, $value)
    {
        return $query->where("quantity", '>=', $value);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    public function scopeSelected($query)
    {
        return $query->select(
            'id',
            'name_en',
            'name_ar',
            'quantity',
            'price',
            'product_image'
        );
    }
}
