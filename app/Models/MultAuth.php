<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultAuth extends Model
{
    use HasFactory;
    protected $table = 'multauth_users';
    protected $fillable = [
        'id_admin_or_user',
        'type',
        'otp',
        'is_admin'
    ];
}
