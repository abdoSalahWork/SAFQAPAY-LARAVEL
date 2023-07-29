<?php

namespace App\Services\Invoice;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItem;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Models\setting\Country;
use App\Services\ConvertCurrencyService;
use App\Services\DiscountService;
use App\Services\SendMailService;
use Illuminate\Support\Facades\DB;

class UpdateInvoiceService
{
    private $sendMailServiceClass, $convertCurrencyServiceClass;
    private $DiscountServiceClass;

    function __construct(SendMailService $sendMailService, DiscountService $DiscountService, ConvertCurrencyService $convertCurrencyService)
    {
        $this->sendMailServiceClass = $sendMailService;
        $this->DiscountServiceClass = $DiscountService;
        $this->convertCurrencyServiceClass = $convertCurrencyService;
    }

    function update($id)
    {
        DB::beginTransaction();

        $user = auth()->user();

        $updateInvoice = Invoice::where('profile_business_id', $user->profile_business_id)
            ->findOrFail($id);

        if ($updateInvoice->status == 'pending') {

            $data = array_diff_key(request()->all(), array_flip(['prductItems']));
            if ($data['send_invoice_option_id'] == 1) {
                $countryCode = Country::select('code')->find($data['customer_mobile_code_id']);
                $data['customer_mobile'] =  $countryCode->code . $data['customer_mobile'];
            }


            //is_open_invoice and calucate invoice value
            if (!$data['is_open_invoice']) {
                $data['invoice_display_value'] = $this->invoiceValue();
            } else {
                $data['invoice_display_value']  = $data['max_invoice'];
            }


            //discount
            if (request()->discount_value > 0) {
                $data['invoice_display_value'] = $this->DiscountServiceClass
                    ->discount($data['invoice_display_value'], request()->discount_type, request()->discount_value);
            }
            //convert
            $data['invoice_value'] = $this->convertCurrencyServiceClass->convertFromDisplayValueToInvoceValue($user->profile_business_id, $data['currency_id'], $data['invoice_display_value']);


            $data['attach_file'] = request()->attach_file ? time() . '.' . request()->file('attach_file')->getClientOriginalExtension() : null;
            if ($data['attach_file']) {
                request()->file('attach_file')->storeAs("public/files/invoice/" . $user->profile_business_id, $data['attach_file']);
            }

            //update invoice
            $update_invoice = $updateInvoice->update($data);

            if ($update_invoice && request()->prductItems) {
                //update product Items
                $this->update_product_items($id);

                //send email
                $this->send_email($updateInvoice);
            }


            DB::commit();
        } else {
            return response()->json([
                "message" => "This Invoice Is Not Pending Can't Update"
            ], 404);
        }
    }


    public function send_email($updateInvoice)
    {

        $user = auth()->user();

        $text = "Employee " . $user->full_name . " has udpate Invoice";
        $type = 'update_invoice';
        $column = 'notification_create_invoice';


        $this->sendMailServiceClass->infoMail($updateInvoice, $text, $type , $column);

        if (request()->recurring_interval_id != 1) {

            $textRecurring = "Recurring invoice updated for customer($updateInvoice->customer_name)";
            $this->sendMailServiceClass->infoMail($updateInvoice, $textRecurring, $type ,$column);
        }
    }


    public function update_product_items($id)
    {
        InvoiceItem::where('invoice_id', $id)->delete();
        foreach (request()->prductItems as $prductItem) {
            InvoiceItem::create([
                'invoice_id' => $id,
                'product_name' => $prductItem['product_name'],
                'product_quantity' => $prductItem['product_quantity'],
                'product_price' => $prductItem['product_price'],
            ]);
        }
    }


    public function invoiceValue()
    {
        $invoice_display_value  = 0;

        foreach (request()->prductItems as $prductItem) {
            $invoice_display_value +=
                $prductItem['product_quantity'] * $prductItem['product_price'];
        }

        return $invoice_display_value;
    }
}
