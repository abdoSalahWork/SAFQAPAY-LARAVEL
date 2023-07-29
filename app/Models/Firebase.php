<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firebase extends Model
{
    use HasFactory;
    protected $table = 'firebase_tokens';
    protected $fillable = ['user_id','token_user'];

}
