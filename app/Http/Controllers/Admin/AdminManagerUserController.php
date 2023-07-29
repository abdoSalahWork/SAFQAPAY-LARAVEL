<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminManagerUserController extends Controller
{
    public function managers()
    {

        $users = User::get();
        return response()->json($users);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $req = [
            'full_name' => $request->full_name,
            'phone_number_code_manager_id' => $request->phone_number_code_manager_id,
            'phone_number_manager' => $request->phone_number_manager,

            'nationality_id' => $request->nationality_id,

            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,


            'confirm_email' => $request->confirm_email ? true : false,
            'confirm_phone' => $request->confirm_phone ? true : false,
            'is_enable' => $request->is_enable ? true : false,

            'enable_bell_sound' => $request->enable_bell_sound ? true : false,
            'batch_invoices' => $request->batch_invoices ? true : false,
            'deposits' => $request->deposits ? true : false,
            'payment_links' => $request->payment_links ? true : false,
            'profile' => $request->profile ? true : false,
            'users' => $request->users ? true : false,
            'refund' => $request->refund ? true : false,
            'show_all_invoices' => $request->show_all_invoices ? true : false,
            'customers' => $request->customers ? true : false,
            'invoices' => $request->invoices ? true : false,
            'products' => $request->products ? true : false,
            'commissions' => $request->commissions ? true : false,
            'account_statements' => $request->account_statements ? true : false,
            'orders' => $request->orders ? true : false,
            'suppliers' => $request->suppliers ? true : false,
            'notification_create_invoice' => $request->notification_create_invoice ? true : false,
            'notification_invoice_paid' => $request->notification_invoice_paid ? true : false,
            'notification_new_order' => $request->notification_new_order ? true : false,
            'notification_create_batch_invoice' => $request->notification_create_batch_invoice ? true : false,
            'notification_deposit' => $request->notification_deposit ? true : false,
            'notification_create_recurring_invoice' => $request->notification_create_recurring_invoice ? true : false,
            'notification_refund_transfered' => $request->notification_refund_transfered ? true : false,
            'notification_notifications_service_request' => $request->notification_notifications_service_request ? true : false,
            'notification_notifications_hourly_deposit_rejected' => $request->notification_notifications_hourly_deposit_rejected ? true : false,
            'notification_approve_vendor_account' => $request->notification_approve_vendor_account ? true : false,
            'notification_create_shipping_invoice' => $request->notification_create_shipping_invoice ? true : false,
        ];

        $rule = [
            'full_name' => 'required|string|max:255',
            'phone_number_code_manager_id' => 'required|string|max:20',
            'phone_number_manager' => 'required|max:20',
            'nationality_id' => 'required|integer|max:20',

            'password' => 'required|confirmed|string|max:20|min:6',
            
            'confirm_email' => 'boolean',
            'confirm_phone' => 'boolean',
            'is_enable' => 'boolean',

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
        ];

        $data = Validator::make($req, $rule);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        if ($request->password) {
            $req['password'] = Hash::make($request->password);
            $req['password_confirmation'] = Hash::make($request->password_confirmation);
        }



        $user->update($req);

        if ($request->avatar) {
            $data2 = Validator::make([
                'avatar' => $request->avatar
            ], [
                'avatar' => 'image|mimes:jpg,png'
            ]);
            if ($data2->fails()) {
                return response()->json($data->errors(), 404);
            }
            $fileName = time() . '.' . $request->avatar->extension();
            User::find($id)->update([
                'avatar' => $fileName
            ]);
            $request->file('avatar')->storeAs("public/images/users/$id", $fileName);
        }

        return response()->json([
            'message' => 'sucsess'
        ]);
    }
}
