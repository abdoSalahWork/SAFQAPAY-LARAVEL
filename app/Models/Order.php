<?php

namespace App\Models;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'order_status',
    ];

    function invoice()
    {
        return $this->hasOne(Invoice::class,'id','invoice_id')->with('transaction')->with('invoiceItem');
    }
}