<?php

namespace App\Models\setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $fillable = ['name_en','name_ar', 'is_active', 'country_id'];
    protected $hidden = ['created_at','updated_at'];

    function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
