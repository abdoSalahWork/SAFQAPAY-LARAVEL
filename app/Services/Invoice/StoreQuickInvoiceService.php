<?php

namespace App\Services\Invoice;

use App\Models\Invoice\Invoice;
use App\Models\setting\Country;
use App\Services\ConvertCurrencyService;
use App\Services\PercentagePaymentService;
use App\Services\SendMailService;
use Illuminate\Support\Carbon;


class StoreQuickInvoiceService
{
    private $sendMailServiceClass, $convertCurrencyServiceClass, $PercentagePaymentServiceClass;

    function __construct(SendMailService $sendMailService, ConvertCurrencyService $convertCurrencyService, PercentagePaymentService $PercentagePaymentService)
    {
        $this->sendMailServiceClass = $sendMailService;
        $this->convertCurrencyServiceClass = $convertCurrencyService;
        $this->PercentagePaymentServiceClass = $PercentagePaymentService;
    }

    function store($request)
    {
        $user = auth()->user();
        if ($request['send_invoice_option_id'] == 1) {
            $countryCode = Country::select('code')->find($request['customer_mobile_code_id']);
            $data['customer_mobile'] =  $countryCode->code . $request['customer_mobile'];
        }

        $request['manager_user_id'] = $user->id;
        $request['profile_business_id'] = $user->profile_business_id;

        // $percentage = $this->PercentagePaymentServiceClass->percentagePayment($request['invoice_value'] ,request()->currency_id);
        // $request['invoice_display_value'] = $percentage + $request['invoice_value'];
        $request['invoice_display_value'] = $request['invoice_value'];
        $request['invoice_value'] = $this->convertCurrencyServiceClass->convertFromDisplayValueToInvoceValue($user->profile_business_id, $request['currency_id'], $request['invoice_display_value']);

        $request['invoice_type'] = 'invoice';
        // $request['customer_mobile'] = $countryCode->code . $request['customer_mobile'];

        $expiry_date = Carbon::parse()->addDays(3);
        $request['expiry_date'] = $expiry_date;
        $invoice = Invoice::create($request);
        $type = 'create_invoice';
        $column = 'notification_create_invoice';



        $text = "Employee " . auth()->user()->full_name . " has created new Invoice";
        $this->sendMailServiceClass->infoMail($invoice, $text, $type, $column);

        return response()->json([
            "message" => "Susess"
        ]);
    }
}
