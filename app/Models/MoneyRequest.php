<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'profile_id','amount','status','Bank_name', 'user_id','type'
    ];
    function profile_information(){
        return $this->hasOne(ProfileBusiness::class , 'id','profile_id');
    }
}
