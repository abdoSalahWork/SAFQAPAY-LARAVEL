<?php

namespace App\Models\setting;

use App\Models\ProfileBusiness;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'profile_id',
        'user_id',
        'full_name',
        'mobile',
        'email',
        'support_type_id',
        'message',
        'image_file',
    ];

    function support_type()
    {

        return $this->hasOne(SupportType::class,'id','support_type_id');
    }

    function profileBusiness(){

        return $this->belongsTo(ProfileBusiness::class , 'profile_id','id' );
    }
}
