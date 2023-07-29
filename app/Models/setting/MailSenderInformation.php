<?php

namespace App\Models\setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailSenderInformation extends Model
{
    use HasFactory;
    protected $fillable = [
        'transport',
        'host',
        'encryption',
        'username',
        'password',
        'address',
        'name',
        'port'
    ];
}
