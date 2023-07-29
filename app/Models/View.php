<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;
    protected $fillable=[
        'invoice_id','view_date_time' , 'ip_address'
    ];
    // function invoice(){
    //     return $this->hasMany(View::class , 'invoiceId');
    // }
}
