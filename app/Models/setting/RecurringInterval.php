<?php

namespace App\Models\setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringInterval extends Model
{
    use HasFactory;

    protected $fillable = ['name_en','name_ar'];
}
