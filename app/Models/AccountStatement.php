<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountStatement extends Model
{
    use HasFactory;
    protected $fillable = [
        'profile_id',
        'reference_number' , 
        'Description',
        'Debit',
        'Credit',
        'Balance'
    ];

    function profile(){

        return $this->belongsTo(ProfileBusiness::class , 'profile_id','id' );
    }
}
