<?php

namespace App\Services\Invoice;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItem;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Models\setting\Country;
use App\Services\ConvertCurrencyService;
use App\Services\DiscountService;
use App\Services\PercentagePaymentService;
use App\Services\SendMailService;
use App\Services\SendSmsService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StoreInvoiceService
{
    private $sendMailServiceClass, $convertCurrencyServiceClass;
    private $DiscountServiceClass;
    private $sendSmsServiceClass;
    private $PercentagePaymentServiceClass;

    function __construct(SendMailService $sendMailService, DiscountService $DiscountService, ConvertCurrencyService $convertCurrencyService, SendSmsService $sendSmsService, PercentagePaymentService $PercentagePaymentService)
    {
        $this->sendMailServiceClass = $sendMailService;
        $this->DiscountServiceClass = $DiscountService;
        $this->convertCurrencyServiceClass = $convertCurrencyService;
        $this->sendSmsServiceClass = $sendSmsService;
        $this->PercentagePaymentServiceClass = $PercentagePaymentService;
    }

    function store($request)
    {
        // flip data in invoice
        $data = array_diff_key($request, array_flip(['prductItems']));

        $user = auth()->user();

        // Get Invoice Value
        $data['invoice_display_value'] = $this->invoiceValue();

        if ($data['invoice_display_value'] <= 0) {
            return response()->json([
                "message" => "discount must be less than invoice value"
            ], 404);
        }

        // Handel Data Invoice

        $data = $this->handelInvoice($data, $user);

        if ($data['is_open_invoice'] and $data['invoice_display_value'] <= $data['min_invoice']) {
            return response()->json([
                "message" => "min invoice must be less than max invoice value"
            ], 404);
        }

        // Insert Data Invoice
        $data = $this->insertInvoice($data);

        return response()->json([
            "message" => "Susess"
        ]);
    }

    public function create_product_items($id)
    {
        foreach (request()->prductItems as $prductItem) {
            InvoiceItem::create([
                'invoice_id' => $id,
                'product_name' => $prductItem['product_name'],
                'product_quantity' => $prductItem['product_quantity'],
                'product_price' => $prductItem['product_price'],
            ]);
        }
    }

    private function invoiceValue()
    {
        $invoice_display_value  = 0;
        foreach (request()->prductItems as $prductItem) {
            $invoice_display_value +=
                $prductItem['product_quantity'] * $prductItem['product_price'];
        }

        if (request()->discount_value > 0) {
            $invoice_display_value = $this->DiscountServiceClass
                ->discount($invoice_display_value, request()->discount_type, request()->discount_value);
        }

        // $percentage = $this->PercentagePaymentServiceClass->percentagePayment($invoice_display_value, request()->currency_id);
        // return $invoice_display_value + $percentage;
        return $invoice_display_value;
    }

    public function send_email($createInvoice)
    {

        $user = auth()->user();

        $text = "Employee " . $user->full_name . " has created new Invoice";
        $type = 'create_invoice';
        $column = 'notification_create_invoice';


        $this->sendMailServiceClass->infoMail($createInvoice, $text, $type, $column);


        if (request()->recurring_interval_id != 1) {

            $textRecurring = "Recurring invoice created for customer($createInvoice->customer_name)";
            $this->sendMailServiceClass->infoMail($createInvoice, $textRecurring, $type, $column);
        }
    }
    private function handelInvoice($data, $user)
    {

        if ($data['is_open_invoice']) {
            $data['expiry_date'] = Carbon::now()->addYear();
            $data['max_invoice'] = $data['invoice_display_value'];
        }



        $data['manager_user_id']  = $user->id;
        $data['profile_business_id']  = $user->profile_business_id;
        $data['invoice_type'] = 'invoice';


        $data['invoice_value'] = $this->convertCurrencyServiceClass->convertFromDisplayValueToInvoceValue($user->profile_business_id, $data['currency_id'], $data['invoice_display_value']);


        $data['attach_file'] = request()->attach_file ? time() . '.' . request()->file('attach_file')->getClientOriginalExtension() : null;
        if (request()->hasFile('attach_file')) {
            request()->file('attach_file')->storeAs("public/files/invoice/" . $user->profile_business_id, $data['attach_file']);
        }

        return $data;
    }
    private function insertInvoice($data)
    {
        DB::beginTransaction();
        $createInvoice = Invoice::create($data);
        //create Product Items
        if ($createInvoice and request()->prductItems) {
            $this->create_product_items($createInvoice->id);
            $this->send_email($createInvoice);

            if ($data['send_invoice_option_id'] == 2) {
                $this->sendMailServiceClass->send_email_customer_invoice($createInvoice);
            } else if ($data['send_invoice_option_id'] == 1) {
                $countryCode = Country::select('code')->find($data['customer_mobile_code_id']);
                $data['customer_mobile'] =  $countryCode->code . $data['customer_mobile'];
                $text = "New Invoice created fro this number";
                $url = 'safqapay.com/payInvoice/' . $createInvoice->id;
                $this->sendSmsServiceClass->sendSMS($data['customer_mobile'], $createInvoice->id, $text, $url);
            }
        }
        DB::commit();
        // End  Create
        return true;
    }
}
