<?php

namespace App\Models\setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = ['name_en','name_ar','code','nationality_en','nationality_ar','flag','currency','short_currency','country_active','short_name'] ;

    protected $hidden = ['created_at','updated_at'];
}
