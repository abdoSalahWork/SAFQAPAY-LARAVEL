<?php

namespace App\Models\setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $fillable = [
        'country',
        'city',
        'area',
        'block',
        'avenue',
        'street',
        'sales_support_officer_info',
        'support_email'
    ];
}
