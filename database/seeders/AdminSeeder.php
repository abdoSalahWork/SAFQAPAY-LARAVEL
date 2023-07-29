<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Commission;
use App\Models\MultAuth;
use App\Models\PaymentInformation;
use App\Models\setting\MailSenderInformation;
use App\Models\WalletAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        MailSenderInformation::create([
            'transport' => 'smtp',
            'host' =>  'smtp.sendgrid.net',
            'port' =>  465,
            'encryption' =>  'ssl',
            'username' => 'apikey',
            'password' => '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',
            'address' => 'support@safqapay.com',
            'name' => 'SAFQA TEAM',
        ]);
        $admin = Admin::create([
            "is_super_admin" => 1,
            "name" => "Admin Safqa Pay",
            "email" => "safqa.payment@gmail.com",
            "password" => Hash::make("123456789"),
            "phone" => "010962615",
            "phone_number_code_id" => "7",
            'wallet' => true,
            'admins' => true,
            'profiles' => true,
            'invoices' => true,
            'refunds' => true,
            'addresses' => true,
            'languages' => true,
            'banks' => true,
            'business_categories' => true,
            'business_types' => true,
            'payment_methods' => true,
            'social_media' => true,
        ]);
        WalletAdmin::create(['safqa_wallet' => 0]);
        MultAuth::create([
            "id_admin_or_user" => $admin->id,
            "type" => 0,
            "otp" => 0,
            "is_admin" => 1,

        ]);

        Commission::create([
            'safqa_commission' => 1,
            'payment_commission' => 4,
        ]);
        PaymentInformation::create([
            'payment_key' => 'enter your payment key ',
            'payment_secret' => 'enter your payment secret',
        ]);
    }
}
