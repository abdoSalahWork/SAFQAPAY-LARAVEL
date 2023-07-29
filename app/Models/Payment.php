<?php

namespace App\Models;

use App\Models\Invoice\Invoice;
use App\Models\setting\Country;
use App\Models\setting\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'manager_user_id',
        'profile_business_id',
        'payment_title',
        'payment_amount',
        'currency_id',
        'language_id',
        'open_amount',
        'comment',
        'terms_and_conditions',
        'max_amount',
        'min_amount'
    ];

    function currency()
    {
        return $this->belongsTo(Country::class,'currency_id','id')->select('id','currency','short_currency');
    }
    function language()
    {
        return $this->belongsTo(Language::class,'language_id','id');
    }
    function profile(){
        
        return $this->belongsTo(ProfileBusiness::class , 'profile_business_id','id' );
    }
    function transaction()
    {
        return $this->belongsToMany(activity::class, PaymentInvoice::class, 'payment_id', 'invoice_id','id','invoice_id');

    }
    function view()
    {
        return $this->belongsToMany(View::class, PaymentInvoice::class, 'payment_id', 'invoice_id','id','invoice_id');
    }
}
