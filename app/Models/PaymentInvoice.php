<?php

namespace App\Models;

use App\Models\Invoice\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInvoice extends Model
{
    use HasFactory;
    protected $fillable =[
        'payment_id',
        'invoice_id',
    ];
  
}
