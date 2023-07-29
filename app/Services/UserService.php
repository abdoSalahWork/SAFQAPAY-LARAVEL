<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserService
{

    public function store($profileBusinessID)
    {

       return User::create([

            'profile_business_id' => $profileBusinessID,
            'email' => request()->email,
            'full_name' => request()->full_name,
            'phone_number_code_manager_id' => request()->phone_number_code_manager_id,
            'phone_number_manager' => request()->phone_number_manager,
            'password' => Hash::make(request()->password),
            'nationality_id' => request()->nationality_id,
            'avatar' => '',
            'enable_bell_sound' => true,
            'confirm_email' => false,
            'confirm_phone' => false,

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
            'role_id' => 2,
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
            'notification_create_shipping_invoice' => true
        ]);
    }
}
