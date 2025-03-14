<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;
    protected $table = 'password_resets';
    // public $timestamp = false;
    protected $fillable =[
        'sender',
        'type',
        'created_at'
    ];
    public $timestamps = false;
}
