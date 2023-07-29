<?php

namespace App\Models;

use App\Models\setting\Bank;
use App\Models\setting\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'email',
        'customer_reference',
        'phone_number_code_id',
        'phone_number',
        'bank_id',
        'bank_account',
        'iban',
        'manager_user_id',
        'profile_business_id'
    ];
    protected $hidden = ['created_at','updated_at' , 'phone_number_code_id' , 'bank_id'];
    function country()
    {
        return $this->belongsTo(Country::class, 'phone_number_code_id', 'id');
    }
    function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
}
