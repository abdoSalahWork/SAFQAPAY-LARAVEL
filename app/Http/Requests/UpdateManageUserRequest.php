<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UpdateManageUserRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $manageUser = User::findOrFail(request()->route()->id);

        $validtion = Validator::make(request()->all(), [
            'role_id' => 'required|integer|exists:user_roles,id',
            'full_name' => 'required|string|max:255',
            // 'email' => "required|string|email|unique:users,email,$manageUser->id",
            // 'phone_number_code_manager_id' => "required|string|max:20|exists:countries,id",
            // 'phone_number_manager' => "required|max:20|unique:users,phone_number_manager, $manageUser->id",
            'nationality_id' => 'required|integer|exists:countries,id',


            'is_enable' => 'required|boolean',

            'enable_bell_sound' => 'boolean',
            'batch_invoices' => 'boolean',
            'deposits' => 'boolean',
            'payment_links' => 'boolean',
            'profile' => 'boolean',
            'users' => 'boolean',
            'refund' => 'boolean',
            'show_all_invoices' => 'boolean',
            'customers' => 'boolean',
            'invoices' => 'boolean',
            'products' => 'boolean',
            'commissions' => 'boolean',
            'account_statements' => 'boolean',
            'orders' => 'boolean',
            'suppliers' => 'boolean',

            'notification_create_invoice' => 'boolean',
            'notification_invoice_paid' => 'boolean',
            'notification_new_order' => 'boolean',
            'notification_create_batch_invoice' => 'boolean',
            'notification_deposit' => 'boolean',
            'notification_create_recurring_invoice' => 'boolean',
            'notification_refund_transfered' => 'boolean',
            'notification_notifications_service_request' => 'boolean',
            'notification_notifications_hourly_deposit_rejected' => 'boolean',
            'notification_approve_vendor_account' => 'boolean',
            'notification_create_shipping_invoice' => 'boolean',

        ]);
        if ($validtion->fails()) {
            return ['message' => $validtion->errors()];
        }

        return [];
    }
}
