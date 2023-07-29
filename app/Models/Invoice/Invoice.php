<?php

namespace App\Models\Invoice;

use App\Models\activity;
use App\Models\Order;
use App\Models\ProfileBusiness;
use App\Models\setting\Country;
use App\Models\setting\Language;
use App\Models\setting\RecurringInterval;
use App\Models\Transaction;
use App\Models\User;
use App\Models\View;
use App\Observers\InvoiceObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_user_id',
        'profile_business_id',
        'customer_name',
        'customer_mobile',
        'customer_email',
        'customer_reference',
        'discount_type',
        'discount_value',
        'min_invoice',
        'max_invoice',
        'expiry_date',
        'attach_file',
        'remind_after',
        'comments',
        'terms_and_conditions',
        'send_invoice_option_id',
        'recurring_interval_id',
        'recurring_start_date',
        'recurring_end_date',
        'start_date',
        'end_date',
        'currency_id',
        'language_id',
        'is_open_invoice',
        'invoice_value',
        'invoice_display_value',
        'status',
        'is_order',
        'invoice_type',
        'civil_id',
        'last_sent_date',
        'amount_changable',
        'refund_amount'

    ];

    protected $hidden = ['currency_id'];

    function currency()
    {
        return $this->belongsTo(Country::class,'currency_id','id')->select('id','currency','short_currency');
    }
    function send_invoice_option()
    {
        return $this->belongsTo(SendInvoiceOption::class,'send_invoice_option_id','id')->select('id','name_en','name_ar' , 'default');
    }

    function language()
    {
        return $this->belongsTo(Language::class,'language_id','id');
    }
    function recurring_interval()
    {
        return $this->belongsTo(RecurringInterval::class,'recurring_interval_id','id')->select('id','name_ar' , 'name_en');
    }

    function invoice_item()
    {
        return $this->hasMany(InvoiceItem::class,'invoice_id','id');
    }

    // public function transaction()
    // {

    //     return $this->hasMany(Transaction::class,'id');
    // }

    function vendor()
    {
        return $this->belongsTo(User::class,'manager_user_id','id')->select('id','full_name');
    }
    function view(){
        return $this->hasMany(View::class , 'invoice_id');
    }
    function profile(){

        return $this->belongsTo(ProfileBusiness::class , 'profile_business_id','id' );
    }

    function transcation(){
        return $this->hasMany(activity::class , 'invoice_id');
    }

    protected static function boot(){
        parent::boot();
        Invoice::observe(InvoiceObserver::class);
    }
    function scopeSelected($query)
    {
        return $query->select('id', 'profile_business_id', 'created_at', 'customer_name', 'customer_mobile', 'customer_email', 'invoice_value', 'invoice_display_value', 'invoice_type', 'currency_id', 'status', 'terms_and_conditions','discount_type','discount_value' , 'is_open_invoice' , 'min_invoice' , 'max_invoice' , 'comments' , 'amount_changable');

    }
}
