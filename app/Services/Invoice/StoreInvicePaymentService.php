<?php

namespace App\Services\Invoice;

use App\Models\Invoice\Invoice;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Models\Payment;
use App\Models\PaymentInvoice;
use App\Models\ProfileBusiness;
use App\Models\setting\Country;
use App\Services\DiscountService;
use Illuminate\Support\Carbon;


class StoreInvicePaymentService
{
    private $DiscountServiceClass;
    function __construct(DiscountService $DiscountService)
    {
        $this->DiscountServiceClass = $DiscountService;
    }


    function store($request, $payment_id)
    {
        $user = auth()->user();

        $payment = Payment::find($payment_id);
        if ($payment) {
            // $invoice_value = $this->convertCurrencyServiceClass->convertCurrency($profile_id, $payment->currency_id, $payment->payment_amount);
            $expiry_date = Carbon::parse()->addDays(3);

            $request['manager_user_id'] = $payment->manager_user_id;
            $request['profile_business_id'] = $payment->profile_business_id;
            $request['expiry_date'] = $expiry_date;
            $request['invoice_display_value'] = $payment->payment_amount;
            $request['invoice_value'] = $payment->payment_amount;
            $request['language_id'] = $payment->language_id;
            $request['currency_id'] = $payment->currency_id;
            $request['customer_mobile'] = $request['customer_mobile'];
            $request['invoice_type'] = 'payment_invoice';
            if ($payment->open_amount == 1) {
                $request['is_open_invoice'] = $payment->open_amount;
                $request['min_invoice'] = $payment->min_amount;
                $request['max_invoice'] = $payment->max_amount;
            }
            $invoice = Invoice::create($request->all());
            PaymentInvoice::create([
                'payment_id' => $payment->id,
                'invoice_id' => $invoice->id,
            ]);
            return $invoice;
        }
    }
}
