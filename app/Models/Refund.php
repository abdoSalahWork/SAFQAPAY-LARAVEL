<?php

namespace App\Models;

use App\Models\Invoice\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'profile_id',
        'amount',
        'comments',
        'status',
        'refund_stripe_id',
        'IsDeductRefundChargeFromCustomer',
        'IsDeductServiceChargeFromCustomer',
    ];
    function invoice()
    {
        return $this->belongsTo(Invoice::class,'invoice_id','id');
    }
}
