<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLinkCategryProduct extends Model
{
    use HasFactory;

    protected $fillable = ['product_link_id','product_id'];
    function products()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
