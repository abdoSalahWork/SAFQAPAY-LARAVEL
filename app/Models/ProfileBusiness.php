<?php

namespace App\Models;

use App\Models\additional\socialMediaProfile;
use App\Models\setting\Bank;
use App\Models\setting\BusinessType;
use App\Models\setting\Category;
use App\Models\setting\Country;
use App\Models\setting\DepositTerm;
use App\Models\setting\InvoiceExpiryAfterType;
use App\Models\setting\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ProfileBusiness extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'country_id', 'phone_number_code_id', 'business_type_id',
        'category_id', 'invoice_expiry_after_number', 'invoice_expiry_after_type_id',
        'language_id', 'deposit_terms_id',
        'company_name',
        'name_en', 'name_ar',
        'logo', 'website_url', 'work_email', 'phone_number',
        'custom_sms_ar', 'custom_sms_en',
        'terms_and_conditions', 'products_delivery_fees', 'promo_code',
        'bank_account_name', 'bank_id', 'account_number', 'iban','bank_account_letter','card_stripe_id',
        'theme_color', 'enable_new_design',  'show_all_currencies', 'enable_card_view', 'is_approval', 'is_enable'
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];



    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function phoneNumberCode()
    {
        return $this->hasOne(Country::class, 'id', 'phone_number_code_id');
    }
    public function businessType()
    {
        return $this->hasOne(BusinessType::class, 'id', 'business_type_id');
    }
    public function bank()
    {
        return $this->hasOne(Bank::class, 'id', 'bank_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function invoiceExpiryAfterType()
    {
        return $this->hasOne(InvoiceExpiryAfterType::class, 'id', 'invoice_expiry_after_type_id');
    }

    public function language()
    {
        return $this->hasOne(Language::class, 'id', 'language_id');
    }

    public function depositTerms()
    {
        return $this->hasOne(DepositTerm::class, 'id', 'deposit_terms_id');
    }

    public function managerUsers()
    {
        return $this->hasMany(User::class, 'profile_business_id','id');
    }
    public function scopeSelected($query)
    {
        return $query->select('id','logo', 'company_name', 'work_email', 'website_url','phone_number');
    }


    function money_request(){
        return $this->hasMany(MoneyRequest::class,'profile_id' , 'id');
    }
    function wallet(){
        return $this->hasOne(Wallet::class,'profile_id' , 'id');
    }
    function aboutStore(){
        return $this->hasOne(AboutStore::class,'profile_id' , 'id');
    }
}
