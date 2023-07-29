<?php

namespace App\Http\Controllers;

use App\Models\Invoice\Invoice;
use App\Models\View;
use App\Services\ActivityService;
use App\Services\ConvertCurrencyService;
use App\Services\NotificationService;
use App\Services\reccurringService;
use App\Services\SendMailService;
use App\Services\StripeService;
use App\Services\WalletService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller

{
    private $stripeService;
    private $walletService;
    private $ActivityService;
    private $reccurringService;
    private $notificationServiceClass;
    private $sendMailService;
    private $convertCurrencyServiceClass;




    public function __construct(StripeService $stripeService, WalletService $wallet_service, ActivityService $Activity_service, reccurringService $reccurringService, NotificationService $notificationService, SendMailService $sendMailService, ConvertCurrencyService $convertCurrencyService)

    {
        $this->stripeService = $stripeService;
        $this->walletService = $wallet_service;
        $this->ActivityService = $Activity_service;
        $this->reccurringService = $reccurringService;
        $this->notificationServiceClass = $notificationService;
        $this->sendMailService = $sendMailService;
        $this->convertCurrencyServiceClass = $convertCurrencyService;
    }

    public function show($invoice_id)
    {

        $invoice = Invoice::selected()
            ->with(['invoice_item', 'currency', 'profile' => function ($q) {
                $q->selected();
                $q->with(['aboutStore' => function ($q) {
                    $q->where('is_active', true);
                }]);
            }])->findOrFail($invoice_id);
        if ($invoice) {
            View::create([
                'invoice_id' => $invoice_id,
                'ip_address' => request()->ip(),
                'view_date_time' => Carbon::now()->toDateString()
            ]);
        }
        if (!$invoice->invoice_item->count()) {
            $invoice->invoice_item[] = [
                'product_name' => 'Pay For' . $invoice->profile->company_name,
                'product_quantity' => 1,
                'product_price' => $invoice->invoice_display_value
            ];
        }
        $urlLogoStore = url("image/aboutStore/");

        return response()->json(['data' => $invoice, 'urlLogoStore' => $urlLogoStore]);;
    }

    public function chargeInvoice(Request $request, $invoice_id)
    {
        $invoice =  Invoice::with('currency:id,short_currency')->findOrFail($invoice_id);
        $validateData = [
            'is_open_invoice' => 'boolean',
            'card_name' => 'required|string|min:9|max:50',
            'card_number' => 'required|string|min:16|max:16',
            'cvc' => 'required|string|min:3|max:4',
            'exp_month' => 'required|string|date_format:m',
            'exp_year' => "required|string|date_format:Y",
            'date_card' => 'after:' . Carbon::now()->toDateString(),
            'amount' => "nullable" #-$invoice->ammountC
        ];
        if ($invoice->is_open_invoice) {
            $validateData['amount'] = "required_if:is_open_invoice,==,1|gte:$invoice->min_invoice |lte: $invoice->max_invoice";
        }
        $requestData = [
            'is_open_invoice' => $invoice->is_open_invoice,
            'card_name' => $request->card_name,
            'card_number' => $request->card_number,
            'cvc' => $request->cvc,
            'exp_month' => $request->exp_month,
            'exp_year' => $request->exp_year,
            'date_card' =>  "$request->exp_year-$request->exp_month",
            'amount' => $request->amount,
        ];
        $data = Validator::make($requestData, $validateData);
        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }
        $requestData['amount'] = $invoice->is_open_invoice ? $request->amount : $invoice->invoice_display_value;
        try {
            DB::beginTransaction();

            $invoice = $this->reccurringService->reccurring($invoice);

            if (!$invoice) {
                return response()->json(['message' => 'this invoice can not paid'], 404);
            }
            //changable invoice value
            if ($invoice->is_open_invoice == 1) {
                $invoice = $this->changable_invoice($request->amount, $invoice);
                if ($invoice['message']) {
                    return response()->json(['message' => $invoice['message']], 404);
                }
            }
            $cardData = $this->stripeService->createTokenCard($request->all());
            // $this->stripeService->stripeFee($cardData->card->brand);
            $charge = $this->stripeService->stripeCharge($cardData->id, $requestData['amount'], $invoice->currency->short_currency);
            $retrieve = $this->stripeService->stripeChargeRetrieve($charge->id);
            // return $retrieve->payment_method_details->card->brand;

            $walletId = $this->walletService->wallet($invoice->profile_business_id, $retrieve->balance_transaction->net / 100, $retrieve->amount / 100, $retrieve, $invoice->id);

            if ($walletId) {
                $message = $this->ActivityService->activity($invoice, $walletId, $request->card_name, $charge['id'], $retrieve->payment_method_details->card->brand , $requestData['amount']);
                DB::commit();
                $this->notificationInvoicePaid($invoice);
                return $message;
            }
            return response()->json(['message' => 'wallet is failed'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }


    public function changable_invoice($amount, $invoice)
    {

        //check in expiry date
        if ($amount + $invoice->amount_changable <= $invoice->invoice_display_value) {

            // $invoice->invoice_value = $this->convertCurrencyServiceClass->convertFromDisplayValueToInvoceValue($invoice->profile_business_id, $invoice->currency_id, $amount);
            if ($invoice->amount_changable + $amount == $invoice->invoice_display_value) {
                $invoice->update([
                    'status' => 'paid',
                    'amount_changable' => $invoice->amount_changable + $amount

                ]);
            } else {
                $invoice->update([
                    'status' => 'processing',
                    'amount_changable' => $invoice->amount_changable + $amount
                ]);
            }
            return $invoice;
        } else {
            return ['message' => 'this amount not valid'];
        }
    }

    public function notificationInvoicePaid($invoice)
    {
        $domain = URL::to('/');
        $api = "$domain/api/invoice/show/$invoice->id";
        $text = $invoice->customer_name . " Paid Invoice " . $invoice->id;
        $column = 'notification_invoice_paid';
        $vendor = $invoice->manager_user_id ? $invoice->vendor->full_name : $invoice->profile->company_name;
        $this->notificationServiceClass->notification($invoice->id, $invoice->profile_business_id, $text, $api, $column, $vendor);
        $this->sendMailService->send_email_invoicePaid($invoice);
    }
}
