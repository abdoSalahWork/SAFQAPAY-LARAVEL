<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLinkInvoice extends Model
{
    use HasFactory;
    protected $fillable =[
        'product_link_id',
        'invoice_id',
    ];
}
