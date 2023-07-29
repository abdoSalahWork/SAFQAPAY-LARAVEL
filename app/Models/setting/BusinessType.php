<?php

namespace App\Models\setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessType extends Model
{
    use HasFactory;
    protected  $fillable = ['name_en','name_ar','business_logo','default'] ;
    protected $hidden = ['created_at','updated_at'];
}
