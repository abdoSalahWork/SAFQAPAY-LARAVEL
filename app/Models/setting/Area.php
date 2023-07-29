<?php

namespace App\Models\setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $fillable = ['name_en','name_ar','city_id'] ;
    protected $hidden = ['created_at','updated_at','city_id'];

    function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
