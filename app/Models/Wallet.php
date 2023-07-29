<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable =[
        'profile_id','total_balance', 'awating_transfer','total_balance_doller','awating_transfer_doller'
        // ,'transfered'
    ];

    function profile(){
        return $this->belongsTo(ProfileBusiness::class,'profile_id' , 'id');
    }
}
