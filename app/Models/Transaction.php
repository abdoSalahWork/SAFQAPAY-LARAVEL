<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'profile_id',
        'transaction_status',
        'payment_gateway',
        'card_holder_name',
        'card_number',
        'expiration_date',
        'security_code',
        'payment_number',
        'payment_id',
        'authorization_id',
        'track_iD',
        'reference_id',
        'error'
    ];
   
}