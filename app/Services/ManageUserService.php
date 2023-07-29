<?php

namespace App\Services;

use App\Models\setting\MailSenderInformation;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;




class ManageUserService
{

    private $SendMailService;
    public function __construct(SendMailService $SendMailService,)
    {
        $this->SendMailService = $SendMailService;
    }

    public function manageUser()
    {


        $user = auth()->user();

        $req = [
            'profile_business_id' => $user->profile_business_id,
            'role_id' => request()->role_id,
            'full_name' => request()->full_name,
            'email' => request()->email,
            'phone_number_code_manager_id' => request()->phone_number_code_manager_id,
            'phone_number_manager' => request()->phone_number_manager,
            'nationality_id' => request()->nationality_id,

            'enable_bell_sound' => true, //
            'batch_invoices' =>  true,
            'deposits' =>  true,
            'payment_links' =>  true,
            'profile' =>  true,
            'users' =>  true,
            'refund' => true,
            'show_all_invoices' =>  true,
            'customers' =>  true,
            'invoices' =>  true,
            'products' =>  true,
            'commissions' =>  true,
            'account_statements' => true,
            'orders' =>  true,
            'suppliers' =>  true,

            'notification_create_invoice' => request()->notification_create_invoice ? true : false,
            'notification_invoice_paid' => request()->notification_invoice_paid ? true : false,
            'notification_new_order' => request()->notification_new_order ? true : false,
            'notification_create_batch_invoice' => request()->notification_create_batch_invoice ? true : false,
            'notification_deposit' => request()->notification_deposit ? true : false,
            'notification_create_recurring_invoice' => request()->notification_create_recurring_invoice ? true : false,
            'notification_refund_transfered' => request()->notification_refund_transfered ? true : false,
            'notification_notifications_service_request' => request()->notification_notifications_service_request ? true : false,
            'notification_notifications_hourly_deposit_rejected' => request()->notification_notifications_hourly_deposit_rejected ? true : false,
            'notification_approve_vendor_account' => request()->notification_approve_vendor_account ? true : false,
            'notification_create_shipping_invoice' => request()->notification_create_shipping_invoice ? true : false,
        ];
        if (request()->role_id == 2) {
            $req['enable_bell_sound'] = request()->enable_bell_sound ? true : false;
            $req['batch_invoices'] = request()->batch_invoices ? true : false;
            $req['invoices'] = request()->invoices ? true : false;
            $req['deposits'] = request()->deposits ? true : false;
            $req['payment_links'] = request()->payment_links ? true : false;
            $req['profile'] = request()->profile ? true : false;
            $req['refund'] = request()->refund ? true : false;
            $req['show_all_invoices'] = request()->show_all_invoices ? true : false;
            $req['customers'] = request()->customers ? true : false;
            $req['products'] = request()->products ? true : false;
            $req['commissions'] = request()->commissions ? true : false;
            $req['account_statements'] = request()->account_statements ? true : false;
            $req['orders'] = request()->orders ? true : false;
            $req['users'] = request()->users ? true : false;
            $req['suppliers'] = request()->suppliers ? true : false;
        }

        if (strstr(request()->url(), 'manage_user/update')) {

            $manageUser = User::findOrFail(request()->route()->id);
            $req['is_enable'] = request()->is_enable ? true : false;

            $req['password']  = $manageUser->password;
            if ($user->id ==  request()->route()->id) {
                $req['is_enable'] = 1;
            }
        } else if (strstr(request()->url(), 'manage_user/store')) {

            $req['is_enable'] = true;
            $req['password'] =  Hash::make(Str::random(255));

            $this->sendEmailToResetPassword($req['full_name'], $req['email']);
        }
        return $req;
    }

    public function sendEmailToResetPassword($full_name, $email)
    {
        $url = 'https://safqapay.com/forgetPassword';
        $data['title'] = "Safqa Pay";

        $data['url'] = $url;
        $data['name'] = $full_name;
        $data['email'] = $email;

        $this->SendMailService->getInfoMailConfig();
        Mail::send('sendEmailToResetPasword', ['data' => $data], function ($message) use ($data) {
            $message->to($data['email'])->subject($data['title']);
        });
        
    }
    
}
