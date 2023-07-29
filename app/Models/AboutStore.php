<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutStore extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id', 'title', 'description', 'logo','is_active'
    ];
    
    function products()
    {
        return $this->hasMany(Product::class,'profile_business_id', 'profile_id');
    }

    function productCategories()
    {
        return $this->hasMany(ProductCategory::class,'profile_business_id', 'profile_id');
    }
}
