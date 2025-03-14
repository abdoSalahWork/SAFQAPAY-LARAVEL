<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory;
    protected $fillable = [
        'profile_id',
        'civil_id',
        'civil_id_back',
        'bank_account_letter', 
        'other'
    ];
}
