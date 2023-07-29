<?php

namespace Database\Seeders;

use App\Models\AboutStore;
use App\Models\ApiKey;
use App\Models\ProfileBusiness;
use App\Models\setting\Country;
use App\Models\Wallet;
use App\SafqaTrait\HelperTrait;
use App\Services\StripeService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InstallSeeder extends Seeder
{
    public function run()
    {
        $userRoles = HelperTrait::$userRoles;
        foreach ($userRoles as $userRole) {
            DB::table('user_roles')->insert($userRole);
        }

        $commissionFroms = HelperTrait::$commissionFroms;
        foreach ($commissionFroms as $commissionFrom) {
            DB::table('commission_from')->insert($commissionFrom);
        }
        $paymentMethods = HelperTrait::$paymentMethods;
        foreach ($paymentMethods as $paymentMethod) {
            DB::table('payment_methods')->insert($paymentMethod);
        }
        $contacts = HelperTrait::$contacts;
        foreach ($contacts as $contact) {
            DB::table('contacts')->insert($contact);
        }
        $supportTypes = HelperTrait::$supportTypes;
        foreach ($supportTypes as $supportType) {
            DB::table('support_types')->insert($supportType);
        }
        $abouts = HelperTrait::$abouts;
        foreach ($abouts as $about) {
            DB::table('abouts')->insert($about);
        }


        // $countries = HelperTrait::$countries;
        $countries = Config::get('model_dir.countries');


        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'name_en' => $country['name_en'],
                'name_ar' => $country['name_ar'],
                'code' => $country['code'],
                'nationality_en' => $country['nationality_en'],
                'nationality_ar' => $country['nationality_ar'],
                'flag' => $country['flag'],
                'currency' => $country['currency'],
                'short_currency' => $country['short_currency'],
                'country_active' => $country['country_active'],
                'short_name' => $country['short_name'],
            ]);
        }
        $businessType = HelperTrait::$businessType;
        foreach ($businessType as $x) {
            DB::table('business_types')->insert([
                'name_en' => $x['name_en'],
                'name_ar' => $x['name_ar'],
                'business_logo' => $x['business_logo'],
            ]);
        }
        $addressTypes = HelperTrait::$addressType;
        foreach ($addressTypes as $addressType) {
            DB::table('address_types')->insert($addressType);
        }




        $categories = HelperTrait::$categories;
        foreach ($categories as $x) {
            DB::table('categories')->insert([
                'name_en' => $x['name_en'],
                'name_ar' => $x['name_ar'],
            ]);
        }

        $language = HelperTrait::$language;
        foreach ($language as $x) {
            DB::table('languages')->insert([
                'name' => $x['name'],
                'short_name' => $x['short_name'],
                'slug' => $x['slug'],
            ]);
        }

        $depositTerms = HelperTrait::$depositTerms;
        foreach ($depositTerms as $x) {
            DB::table('deposit_terms')->insert([
                'name_en' => $x['name_en'],
                'name_ar' => $x['name_ar'],
            ]);
        }

        $invoiceExpiryAfterType = HelperTrait::$invoiceExpiryAfterType;
        foreach ($invoiceExpiryAfterType as $x) {
            DB::table('invoice_expiry_after_types')->insert([
                'name_en' => $x['name_en'],
                'name_ar' => $x['name_ar'],
                'is_active' => $x['is_active'],
            ]);
        }

        $socialMedia = HelperTrait::$socialMedia;
        foreach ($socialMedia as $x) {
            DB::table('social_media')->insert([
                'name_en' => $x['name_en'],
                'name_ar' => $x['name_ar'],
                'icon' => $x['icon'],
            ]);
        }

        $profileBusiness = HelperTrait::$profileBusiness;
        foreach ($profileBusiness as $x) {
            // $country = Country::select('id', 'short_name', 'short_currency')->find($x['country_id']);
            // $stripeService = new StripeService;
            // $createAccountStripe = $stripeService->createAccountStripe($country->short_name,$x['work_email']);
            $profile = ProfileBusiness::create([
                'country_id' => $x['country_id'],
                'phone_number_code_id' => $x['phone_number_code_id'],
                'business_type_id' => $x['business_type_id'],
                'category_id' => $x['category_id'],
                'invoice_expiry_after_number' => $x['invoice_expiry_after_number'], // not access user
                'invoice_expiry_after_type_id' => $x['invoice_expiry_after_type_id'], // not access user
                'language_id' => $x['language_id'],
                'deposit_terms_id' => $x['deposit_terms_id'], // not access user

                'company_name' => $x['company_name'],
                'name_en' => $x['name_en'],
                'name_ar' => $x['name_ar'],

                'logo' => $x['logo'], // optional
                'website_url' => $x['website_url'],
                'work_email' => $x['work_email'],
                'phone_number' => $x['phone_number'], //  unique
                'custom_sms_ar' => $x['custom_sms_ar'], // textarea -optional-
                'custom_sms_en' => $x['custom_sms_en'], //textarea -optional-
                'terms_and_conditions' => $x['terms_and_conditions'], // textarea -optional-
                'products_delivery_fees' => $x['products_delivery_fees'],
                'promo_code' => $x['promo_code'], // not access user
                'bank_account_name' => $x['bank_account_name'], // not access user edit
                'bank_id' => $x['bank_id'], // not access user edit
                'account_number' => $x['account_number'], // not access user edit
                // 'card_stripe_id' => $cardStripId,
                'iban' => $x['iban'], // not access user edit
                'bank_account_letter' => $x['bank_account_letter'], // not access user
                'enable_new_design' => $x['enable_new_design'],
                'show_all_currencies' => $x['show_all_currencies'],
                'enable_card_view' => $x['enable_card_view'],
                'theme_color' => $x['theme_color'],
            ]);
            Wallet::create([
                'profile_id' => $profile->id,
                'total_balance' => 0,
                'awating_transfer' => 0,
            ]);
            ApiKey::create([
                'profile_id' => $profile->id,
                'token' => Str::random(255)
            ]);
            AboutStore::create([
                'profile_id' => $profile->id,
                'title' => $profile->company_name,
                'description' => "Welcome, is $profile->company_name store ",
                'logo' => null
            ]);
        }


        $user = HelperTrait::$user;

        foreach ($user as $x) {
            DB::table('users')->insert([
                'profile_business_id' => $x['profile_business_id'], // not access user
                'role_id' => $x['role_id'], // not access user
                'email' => $x['email'], //  unique
                'full_name' => $x['full_name'],
                'phone_number_code_manager_id' => $x['phone_number_code_manager_id'],
                'phone_number_manager' => $x['phone_number_manager'], //  unique
                'password' => Hash::make($x['password']),
                'nationality_id' => $x['nationality_id'],
                'avatar' => $x['avatar'], // optional
                'enable_bell_sound' => $x['enable_bell_sound'],
                'confirm_email' => $x['confirm_email'], // not access user
                'confirm_phone' => $x['confirm_phone'], // not access user
                'batch_invoices' => $x['batch_invoices'],
                'deposits' => $x['deposits'],
                'payment_links' => $x['payment_links'],
                'profile' => $x['profile'],
                'users' => $x['users'],
                'refund' => $x['refund'],
                'show_all_invoices' => $x['show_all_invoices'],
                'customers' => $x['customers'],
                'invoices' => $x['invoices'],
                'products' => $x['products'],
                'commissions' => $x['commissions'],
                'account_statements' => $x['account_statements'],
                'orders' => $x['orders'],
                'suppliers' => $x['suppliers'],
                'notification_create_invoice' => $x['notification_create_invoice'],
                'notification_invoice_paid' => $x['notification_invoice_paid'],
                'notification_new_order' => $x['notification_new_order'],
                'notification_create_batch_invoice' => $x['notification_create_batch_invoice'],
                'notification_deposit' => $x['notification_deposit'],
                'notification_create_recurring_invoice' => $x['notification_create_recurring_invoice'],
                'notification_refund_transfered' => $x['notification_refund_transfered'],
                'notification_notifications_service_request' => $x['notification_notifications_service_request'],
                'notification_notifications_hourly_deposit_rejected' => $x['notification_notifications_hourly_deposit_rejected'],
                'notification_approve_vendor_account' => $x['notification_approve_vendor_account'],
                'notification_create_shipping_invoice' => $x['notification_create_shipping_invoice']
            ]);
        }

        // $banks = HelperTrait::$banks;

        $banks = Config::get('model_dir.banks');

        foreach ($banks as $bank) {
            DB::table('banks')->insert([
                'name_en' => $bank['name_en'],
                'name_ar' => $bank['name_ar'],
                'is_active' => $bank['is_active'],
                'country_id' => $bank['country_id'],
            ]);
        }

        $send_invoice_options = HelperTrait::$send_invoice_options;
        foreach ($send_invoice_options as $send_invoice_option) {
            DB::table('send_invoice_options')->insert([
                'name_en' => $send_invoice_option['name_en'],
                'name_ar' => $send_invoice_option['name_ar']
            ]);
        }

        $recurring_intervals = HelperTrait::$recurring_intervals;
        foreach ($recurring_intervals as $recurring_interval) {
            DB::table('recurring_intervals')->insert([
                'name_en' => $recurring_interval['name_en'],
                'name_ar' => $recurring_interval['name_ar'],
            ]);
        }
    }
}
