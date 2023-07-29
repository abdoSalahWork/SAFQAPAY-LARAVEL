<?php

namespace App\Services;

use App\Models\setting\MailSenderInformation;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;


class SendMailService

{

    private $notificationServiceClass;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationServiceClass = $notificationService;
    }

    function infoMail($Invoice, $text, $type, $column)
    {
        $this->send_email($Invoice, $type);

        $domain = URL::to('/');
        $api = "safqapay.com/dashboard/invoices/$Invoice->id";
        $this->notificationServiceClass->notification($Invoice->id, $Invoice->profile_business_id, $text, $api, $column);
    }

    public function send_email($Invoice, $type)
    {
        $users = User::where('profile_business_id',  $Invoice->profile_business_id)
            ->where('notification_create_invoice', true)
            ->get();

        $domain = URL::to('/');
        $url = "safqapay.com/dashboard/invoices/$Invoice->id";
        $data['url'] = $url;
        $data['customer_name'] = $Invoice->customer_name;
        $data['name'] = auth()->user()->full_name;

        if ($type == 'update_invoice') {
            $data['title'] = "New Invoice Updated";
            $data['created_at'] = $Invoice->updated_at;
            $data['type'] = 'Update';
        } else if ($type == 'create_invoice') {
            $data['title'] = "New Invoice Created";
            $data['created_at'] = $Invoice->created_at;
            $data['type'] = 'Create New';
        }

        $this->getInfoMailConfig();

        foreach ($users as $user) {
            $data['email'] = $user->email;
            Mail::send('invoiceNptification', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
        }
    }

    public function send_email_order($order_id, $Invoice)
    {
        $users = User::where('profile_business_id',  $Invoice->profile_business_id)
            ->where('notification_new_order', true)
            ->get();


        $domain = URL::to('/');
        $url = $domain . '/api/order/show/' . $order_id;
        $data['url'] = $url;
        $data['title'] = "New Order $order_id Recieved";

        $this->getInfoMailConfig();


        foreach ($users as $user) {
            $data['email'] = $user->email;
            Mail::send('sendEmailOrder', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
        }
    }

    public function send_email_refund($refund_id, $profile_business_id, $customer_name)
    {
        $users = User::where('profile_business_id',  $profile_business_id)
            ->where('notification_refund_transfered', true)
            ->get();


        $domain = URL::to('/');
        $url = $domain . '/api/refund/show/' . $refund_id;
        $data['url'] = $url;
        $data['title'] = "Refund Transferd";
        $data['body'] = "Refund $refund_id Transferd To $customer_name  ";

        $this->getInfoMailConfig();


        foreach ($users as $user) {
            $data['email'] = $user->email;
            Mail::send('sendEmailRefund', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
        }
    }

    public function send_email_invoicePaid($invoice)
    {
        $users = User::where('profile_business_id',  $invoice->profile_business_id)
            ->where('notification_refund_transfered', true)
            ->get();


        $domain = URL::to('/');
        $url = $domain . '/api/invoice/show/' . $invoice->id;
        $data['url'] = $url;
        $data['title'] = "Invoice Paid";
        $data['body'] = "Invoice $invoice->id Paid From $invoice->customer_name  ";

        $this->getInfoMailConfig();


        foreach ($users as $user) {
            $data['email'] = $user->email;
            Mail::send('sendEmailPaidInvoice', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
        }
    }


    public function send_email_money_request($money_request_id, $profile_business_id, $user_name)
    {
        $users = User::where('profile_business_id',  $profile_business_id)
            ->where('notification_deposit', true)
            ->get();


        $domain = URL::to('/');
        $api = "$domain/api/deposits";
        $url = $domain . '/api/deposits';
        $data['url'] = $url;
        $data['title'] = "New Deposit";
        $data['body'] = "New Deposit request from $user_name";

        $this->getInfoMailConfig();


        foreach ($users as $user) {
            $data['email'] = $user->email;
            Mail::send('sendEmailDeposit', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
        }
    }


    public function send_email_recurring_interval_invoice($Invoice)
    {

        $domain = URL::to('/');
        $url = $domain . '/api/invoice/show/' . $Invoice->id;
        $data['url'] = $url;
        $data['title'] = 'Recurring Interval Invoice';

        $this->getInfoMailConfig();

        $data['email'] = $Invoice->customer_email;
        Mail::send('sendEmailRecurringInvoice', ['data' => $data], function ($message) use ($data) {
            $message->to($data['email'])->subject($data['title']);
        });
    }

    public function send_email_customer_invoice($Invoice)
    {

        $domain = URL::to('/');
        $url = 'safqapay.com/payInvoice/' . $Invoice->id;
        $data['url'] = $url;
        $data['title'] = 'New Invoice Created';
        $data['name'] = auth()->user()->full_name;
        $data['type'] = 'Create New';
        $data['created_at'] = $Invoice->created_at;
        $data['customer_name'] = $Invoice->customer_name;


        // $this->getInfoMailConfig();

        $data['email'] = $Invoice->customer_email;
        Mail::send('invoiceNptification', ['data' => $data], function ($message) use ($data) {
            $message->to($data['email'])->subject($data['title']);
        });
    }

    public function getInfoMailConfig()
    {
        $data = MailSenderInformation::first();

        Config::set('mail.mailers.smtp.host', $data->host);
        Config::set('mail.mailers.smtp.username', $data->username);
        Config::set('mail.mailers.smtp.password', $data->password);
        Config::set('mail.mailers.smtp.port', $data->port);
        Config::set('mail.mailers.smtp.encryption', $data->encryption);
        Config::set('mail.from.address', $data->address);
        Config::set('mail.from.name', $data->name);
    }
}
