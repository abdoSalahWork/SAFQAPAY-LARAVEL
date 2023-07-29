<?php

namespace App\Models;

use App\Models\setting\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionPaymentMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_method_id',
        'body',
        'commission',
    ];
    protected $hidden = ['created_at','updated_at',];
    
    function paymentMethod()
    {
        return $this->belongsTo(paymentMethod::class, 'payment_method_id', 'id');
    }

   
}
