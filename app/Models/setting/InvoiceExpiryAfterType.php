<?php

namespace App\Models\setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceExpiryAfterType extends Model
{
    use HasFactory;
    protected $fillable = ['name_en','name_ar','is_active'] ;
    protected $hidden = ['created_at','updated_at'];
}
