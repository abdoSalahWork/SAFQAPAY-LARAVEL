<?php

namespace App\SafqaTrait;

use Illuminate\Support\Facades\DB;

class HelperTrait
{

    public static $userRoles = [
        ['name_en' => 'Super Master', 'name_ar' => 'بائع ماستر'],
        ['name_en' => 'User Normal', 'name_ar' => 'بائع مستخدم'],
    ];
    public static $contacts = [
        [
            'country' => 'egypt',
            'city' => 'giza',
            'area' => 'badrashin',
            'block' => 'maraziqe',
            'avenue' => 'tor3a',
            'street' => 'madrsa street',
            'sales_support_officer_info' => '010000000000 said sayed',
            'support_email' => 'said@test.com',
        ]
    ];

    public static $supportTypes = [
        ['name' => 'Account Verification'],
        ['name' => 'Inquiry / Request services'],
        ['name' => 'Financial'],
        ['name' => 'Technical'],
        ['name' => 'Add / Edit Bank Details'],
    ];

    public static $abouts = [
        ['about' => "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like)."],

    ];
    public static $paymentMethods = [
        ['name_en' => 'Visa', 'name_ar' => 'فيزا', 'is_active' => true, 'logo' => 'logo.jpg'],
        ['name_en' => 'Mastercard', 'name_ar' => 'ماستر كارد', 'is_active' => true, 'logo' => 'logo.jpg'],
    ];
    public static $commissionFroms = [
        ['name_en' => 'Vendor', "name_ar" => 'تاجر'],
        ['name_en' => 'Customer', "name_ar" => 'عميل'],
        ['name_en' => 'Split with customer', "name_ar" => 'انقسام مع العميل']
    ];

    public static $banks = [
        ['country_id' => 8, 'is_active' => true, 'name_en' => 'United Emirates Bank' , 'name_ar'=>'ؤضثؤضث'],
        ['country_id' => 1, 'is_active' => true, 'name_en' => 'Abu Dhabi Commercial Bank' , 'name_ar'=>'ؤيشؤث'],
        ['country_id' => 1, 'is_active' => true, 'name_en' => 'Abu Dhabi Islamic Bank' , 'name_ar'=>'يؤ ثيبؤضث'],
        ['country_id' => 1, 'is_active' => true, 'name_en' => 'Arab Bank' , 'name_ar'=>'cwdcdc'],
    ];

    public static $addressType = [
        ['name_en' => 'Not Available', 'name_ar' => 'غيرمتاح'],
        ['name_en' => 'Appartment', 'name_ar' => 'شقة'],
        ['name_en' => 'House', 'name_ar' => 'منزل'],
        ['name_en' => 'Office', 'name_ar' => 'مكتب'],
    ];



    public static  $countries = [
        ['name_ar' => 'السعودية', 'name_en' => 'Saudi Arabia', 'short_name' => 'SAU', 'nationality_ar' => 'سعودى', 'nationality_en' => 'saudian', 'flag' => 'test1', 'code' => '+1', 'currency' => 'Saudi riyal', 'short_currency' => 'SAR','country_active' => false], //Saudi riyal
        ['name_ar' => 'الكويت', 'name_en' => 'Kuwait', 'short_name' => 'KWT', 'nationality_ar' => 'كويتى', 'nationality_en' => 'kuwaitian', 'flag' => 'test2', 'code' => '+3', 'currency' => 'Kuwaiti dinar', 'short_currency' => 'KWD','country_active' => false], // Kuwaiti dinar
        ['name_ar' => 'قطر', 'name_en' => 'Qatar', 'short_name' => 'QAT', 'nationality_ar' => 'قطرى', 'nationality_en' => 'qatarian', 'flag' => 'test3', 'code' => '+4', 'currency' => 'Qatari Riyal', 'short_currency' => 'QAR','country_active' => false], // Qatari Riyal
        ['name_ar' => 'الأردن', 'name_en' => 'Jordan', 'short_name' => 'JOR', 'nationality_ar' => 'اردنى', 'nationality_en' => 'jordion', 'flag' => 'test4', 'code' => '+5', 'currency' => 'Jordanian dinar', 'short_currency' => 'JOD','country_active' => false], // Jordanian dinar
        ['name_ar' => 'البحرين', 'name_en' => 'Bahrain', 'short_name' => 'BHR', 'nationality_ar' => 'بحرينى', 'nationality_en' => 'bahrain', 'flag' => 'tes5', 'code' => '+6', 'currency' => 'Bahraini dinar', 'short_currency' => 'BHD','country_active' => false], // Bahraini dinar
        ['name_ar' => 'عمان', 'name_en' => 'Oman', 'short_name' => 'OMN', 'nationality_ar' => 'عمانى', 'nationality_en' => 'Omani', 'flag' => 'test6', 'code' => '+7', 'currency' => 'Omani rial', 'short_currency' => 'OMR','country_active' => false], // Omani rial
        ['name_ar' => 'مصر', 'name_en' => 'Egypt', 'short_name' => 'EGY', 'nationality_ar' => 'مصرى', 'nationality_en' => 'egyptian', 'flag' => 'test7', 'code' => '+20', 'currency' => 'Egyptian pound', 'short_currency' => 'EGP','country_active' => true], // Egyptian pound
        ['name_ar' => 'الإمارات', 'name_en' => 'United Arab Emirates', 'short_name' => 'UAE', 'nationality_ar' => 'اماراتى', 'nationality_en' => 'Emirati', 'flag' => 'test8', 'code' => '+9', 'currency' => 'Emirates dirham', 'short_currency' => 'AED', 'country_active' => true] // United Arab Emirates dirham
    ];

    public static $businessType = [
        ['name_en' => 'Home Business', 'name_ar' => '', 'business_logo' => 'test.png'],
        ['name_en' => 'Licensed Company', 'name_ar' => '', 'business_logo' => 'test.png'],
    ];

    public static $categories = [
        ['name_ar' => 'تجريبى 1', 'name_en' => 'Airlines'],
        ['name_ar' => 'تجريبى 2', 'name_en' => 'Bakeries'],
        ['name_ar' => 'تجريبى 3', 'name_en' => 'Candy'],
        ['name_ar' => 'تجريبى 4', 'name_en' => 'Caterer'],
    ];

    public static $language = [
        [
            'name' => 'arabic',
            'short_name' => 'AR',
            'slug' => 'ar',
        ],
        [
            'name' => 'english',
            'short_name' => 'EN',
            'slug' => 'en',
        ],
    ];

    public static $depositTerms = [
        ['name_ar' => 'يومي', 'name_en' => 'daily'],
        ['name_ar' => 'أسبوعي', 'name_en' => 'weekly'],
        ['name_ar' => 'شهري', 'name_en' => 'Monthly'],
        ['name_ar' => 'سنوى', 'name_en' => 'annual'],
    ];



    public static $invoiceExpiryAfterType = [
        ['name_ar' => 'سنة', 'name_en' => 'Year' , 'is_active'=>true],
        ['name_ar' => 'شهر', 'name_en' => 'Month' , 'is_active'=>true],
        ['name_ar' => 'اسبوع', 'name_en' => 'Week' , 'is_active'=>true],
        ['name_ar' => 'يوم', 'name_en' => 'Day' , 'is_active'=>true],
        ['name_ar' => 'ساعه', 'name_en' => 'Hour' , 'is_active'=>true],
        ['name_ar' => 'الان', 'name_en' => 'Minute' , 'is_active'=>true],
    ];

    public static $socialMedia = [
        ['name_ar' => 'فيس بوك', 'name_en' => 'facebook', 'icon' => 'fb fb-facebook'],
        ['name_ar' => 'انستغرام', 'name_en' => 'instgram', 'icon' => 'fb fb-instgram'],
    ];

    public static $profileBusiness = [
        [
            'country_id' => 8,
            'phone_number_code_id' => 7,
            'business_type_id' => 1,
            'category_id' => 1,
            'invoice_expiry_after_number' =>  1, // not access user
            'invoice_expiry_after_type_id' => 1, // not access user
            'language_id' => 2,
            'deposit_terms_id' => 2, // not access user

            'company_name' => 'my company 1',
            'name_en' => 'name Ar',
            'name_ar' => 'name En',

            'logo' => 'avatar.png', // optional
            'website_url' => 'http://www.my-website-1.com',
            'work_email' => 'info@safqa.com', //  unique
            'phone_number' => '01060929469', //  unique
            'custom_sms_ar' => 'شكرا على اختياركم شركتى', // textarea -optional-
            'custom_sms_en' => 'thank you for choose my company', //textarea -optional-
            'terms_and_conditions' => 'this some text for terms and condition', // textarea -optional-
            'products_delivery_fees' => 0,
            'promo_code' => 'MI', // not access user
            'bank_account_name' => 'lafi s h m almutairi', // not access user
            'bank_id' => 1, // not access user
            'account_number' => '000123456789', // not access user
            'iban' => 'AE140500000000028677481', // not access user
            'bank_account_letter' => '1.png', // not access user
            'others' => '11.png', // not access user
            'civil_id' => '', // not access user
            'civil_id_back' => '', // not access user
            'enable_new_design' => true,
            'show_all_currencies' => true,
            'enable_card_view' => true,
            'theme_color' => '#00aa00',

            'card_number' => '4242424242424242',
            'exp_month' => 2,
            'exp_year' => 2024,
            'cvc' => '314',
        ],
    ];

    public static $user = [
        [
            'profile_business_id' => 1,
            'role_id' => 1,
            'email' => 'abdo.salah111122@gmail.com', //  unique
            'full_name' => 'Abdelrahman Salah',
            'phone_number_code_manager_id' => 7,
            'phone_number_manager' => '01112530548', //  unique
            'password' => '123456789',
            'nationality_id' => 1,
            'avatar' => 'avatar.png', // optional
            'enable_bell_sound' => true,
            'confirm_email' => true, // not access user
            'confirm_phone' => false, // not access user
            'batch_invoices' => true,
            'deposits' => true,
            'payment_links' => true,
            'profile' => true,
            'users' => true,
            'refund' => true,
            'show_all_invoices' => true,
            'customers' => true,
            'invoices' => true,
            'products' => true,
            'commissions' => true,
            'account_statements' => true,
            'orders' => true,
            'suppliers' => true,
            'notification_create_invoice' => true,
            'notification_invoice_paid' => true,
            'notification_new_order' => true,
            'notification_create_batch_invoice' => true,
            'notification_deposit' => true,
            'notification_create_recurring_invoice' => true,
            'notification_refund_transfered' => true,
            'notification_notifications_service_request' => true,
            'notification_notifications_hourly_deposit_rejected' => true,
            'notification_approve_vendor_account' => true,
            'notification_create_shipping_invoice' => true,

        ]
    ];

    public static $send_invoice_options = [
        ['name_ar' => 'رسالة نصية', 'name_en' => 'sms'],
        ['name_ar' => 'بريد الكترونى', 'name_en' => 'email'],
        ['name_ar' => 'رابط', 'name_en' => 'link'],
    ];
    public static $recurring_intervals = [
        ['name_en' => 'No Recurring', 'name_ar' => 'غير متكررة'],
        ['name_en' => 'weekly', 'name_ar' => 'اسبوعي'],
        ['name_en' => 'Monthly', 'name_ar' => 'شهري'],
    ];

    public static function disableSqlRequirePrimaryKey()
    {
        try {
            DB::statement('SET SESSION sql_require_primary_key=0');
        } catch (\Exception $e) {
            // General error: 1193 Unknown system variable 'sql_require_primary_key'.
            // Do nothing.
        }
    }
}
