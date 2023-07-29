<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebHook extends Model
{
    use HasFactory;
    protected $fillable = [
        'profile_id',
        'enable_webhook',
        'Endpoint',
        'enable_secret_key',
        'webhook_secret_key',
        'transaction_status_changed',
        'balance_transferred',
        'recurring_status_changed',
        'refund_status_changed',
        'supplier_status_changed',
    ];
}
