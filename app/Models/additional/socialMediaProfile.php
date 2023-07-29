<?php

namespace App\Models\additional;

use App\Models\setting\SocialMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class socialMediaProfile extends Model
{
    use HasFactory;
    protected $fillable = ['url','social_id','profile_business_id'] ;
    protected $hidden = ['created_at','updated_at'];

    function socialMedia()
    {
        return $this->belongsTo(SocialMedia::class,'social_id','id');
    }
}
