<?php

namespace App\Models;

use App\Models\Invoice\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLink extends Model
{
    use HasFactory;
    protected $fillable = [
        'profile_id',
        'user_id',
        'name_en',
        'name_ar',
        'is_active',
        'Terms_and_conditions',
        'url_ar',
        'url_en'
    ];

    function Products()
    {
        return $this->belongsToMany(Product::class, ProductLinkCategryProduct::class, 'product_link_id', 'product_id');
    }
    function profile()
    {

        return $this->belongsTo(ProfileBusiness::class, 'profile_id', 'id');
    }
    function transaction()
    {
        return $this->belongsToMany(activity::class, ProductLinkInvoice::class, 'product_link_id', 'invoice_id', 'id', 'invoice_id');
    }
    function view()
    {
        return $this->belongsToMany(View::class, ProductLinkInvoice::class, 'product_link_id', 'invoice_id', 'id', 'invoice_id');
        
    }
}
