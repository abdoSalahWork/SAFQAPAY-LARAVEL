<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'notificationType','type_id','creator_name' , 'profile_id','user_id', 'text' ,'api'
    ];
}
