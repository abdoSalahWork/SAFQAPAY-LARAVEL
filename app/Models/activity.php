<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class activity extends Model
{
    use HasFactory;
    protected $fillable = [
        'profile_id', 'wallet_id', 'invoice_id', 'charge_id', 'card_name', 'status_transfer', 'typeCard', 'transaction_value', 'transaction_value_doller' //true
    ];

    function profile()
    {
        return $this->belongsTo(ProfileBusiness::class, 'profile_id', 'id');
    }
}
