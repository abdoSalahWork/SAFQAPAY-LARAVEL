<?php

namespace App\Models\setting;

use App\Models\CommissionPaymentMethod;
use App\Models\PaymentMethodUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $table = 'payment_methods';
    protected $fillable = [
        'name_en',
        'name_ar',
        'is_active',
        'logo',

    ];
    function payment_method()
    {
        return $this->hasOne(PaymentMethodUser::class, 'payment_method_id', 'id');
    }
    function commissionPaymentMethod()
    {
        return $this->hasMany(CommissionPaymentMethod::class, 'payment_method_id', 'id');
    }
}
