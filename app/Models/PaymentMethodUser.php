<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'commission_from_id',
        'profile_id',
        'payment_method_id',
        'is_active',
        'api_active'
    ];
}
