<?php

namespace App\Models;

use App\Models\setting\AddressType;
use App\Models\setting\Area;
use App\Models\setting\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresse extends Model
{
    use HasFactory;
    protected $fillable = [
        'addressType_id', 'city_id', 'area_id',
        'block', 'avenue','street','bldgNo',
        'appartment','floor','instructions','profile_business_id','manager_user_id'
    ];
    protected $hidden = ['created_at','updated_at','addressType_id','city_id','area_id'];

    function city()
    {
        return $this->belongsTo(City::class,'city_id','id')->select('id','name_en','name_ar');
    }
    function addressType()
    {
        return $this->belongsTo(AddressType::class,'addressType_id','id')->select('id','name_en','name_ar');
    }

    function area()
    {
        return $this->belongsTo(Area::class,'area_id','id')->select('id','name_en','name_ar');
    }

}
