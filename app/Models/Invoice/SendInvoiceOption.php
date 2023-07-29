<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendInvoiceOption extends Model
{
    protected $table = 'send_invoice_options';
    use HasFactory;
    protected $fillable =[
        'name_en',
        'name_ar',
        'default'
    ];
}
