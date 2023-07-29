<?php

namespace App\Models;

use App\Models\setting\Country;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;



class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $guard = 'admin';


    protected $fillable = [
        'is_super_admin', 'name', 'email', 'password', 'phone', 'phone_number_code_id',
        'wallet', 'admins', 'profiles', 'invoices', 'refunds', 'addresses', 'languages',
        'banks', 'business_categories', 'business_types', 'payment_methods', 'social_media'

    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function phoneNumberCode()
    {
        return $this->hasOne(Country::class, 'id', 'phone_number_code_id');
    }
    public function multAuth()
    {
        return $this->hasOne(MultAuth::class, 'id_admin_or_user',  'id');
    }
}
