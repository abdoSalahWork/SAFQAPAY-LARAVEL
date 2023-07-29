<?php

namespace App\Http\Controllers;

use App\Models\AccountStatement;
use App\Models\activity;
use App\Models\Invoice\Invoice;
use App\Models\Refund;
use App\Models\Wallet;
use App\Services\ConvertCurrencyService;
use App\Services\NotificationService;
use App\Services\SendMailService;
use App\Services\StripeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{

    private $stripeService;
    private $notificationServiceClass;
    private $sendMailService;
    private $convertCurrencyServiceClass;


    public function __construct(
        StripeService $stripeService,
        NotificationService $notificationService,
        SendMailService $sendMailService,
        ConvertCurrencyService $convertCurrencyService,
    ) {
        $this->stripeService = $stripeService;
        $this->notificationServiceClass = $notificationService;
        $this->sendMailService = $sendMailService;
        $this->convertCurrencyServiceClass = $convertCurrencyService;
    }


    public function index()
    {
        $user = auth()->user();
        $refunds = Refund::where('profile_id', $user->profile_business_id)->with('invoice')->get();
        return response()->json(['data' => $refunds]);
    }

    public function show($id)
    {
        $refund = Refund::with('invoice')->find($id);
        return $refund ? response()->json(['data' => $refund]) :
            response()->json(['message' => 'refund not found'], 404);
    }

    public function store(Request $request, $invoice_id)
    {

        //get activity and refund
        $user = auth()->user();
        $invoice = Invoice::where('status', 'processing')->where('id', $invoice_id)
            ->orWhere('status', 'paid')->where('id', $invoice_id)->firstOrFail();
        $rules_refund = [
            'invoice_id' => "exists:activities,invoice_id,profile_id, $user->profile_business_id",
            //$invoice->invoice_display_value مكان charge->amount
            'amount' => "required|lte:$invoice->amount_changable",
            'comments' => 'nullable|string',
            'IsDeductRefundChargeFromCustomer' => 'boolean',
            'IsDeductServiceChargeFromCustomer' => 'boolean',
        ];
        $request_refund = [
            'invoice_id' => $invoice_id,
            'profile_id' => $invoice->profile_business_id,
            'amount' => $request->amount,
            'comments' => $request->comments,
            'status' => 'pending',
            'IsDeductRefundChargeFromCustomer' => $request->IsDeductRefundChargeFromCustomer ? true : false,
            'IsDeductServiceChargeFromCustomer' => $request->IsDeductServiceChargeFromCustomer ? true : false,
        ];

        $data_refund = Validator::make($request_refund, $rules_refund);

        if ($data_refund->fails()) {
            return response()->json($data_refund->errors(), 404);
        }


        $total_balance =  $user->profileBusiness->wallet->total_balance;

        $refund_amount_with_currency_profile =  $this->convertCurrencyServiceClass->convertFromDisplayValueToInvoceValue($invoice->profile_business_id, $invoice->currency_id, $request_refund['amount']);
        //check is awating transfer is less than  refund amount
        if ($total_balance < $refund_amount_with_currency_profile) #or $total_balance < 100
        {
            return response()->json(['message' => 'you can not do refund From Wallet'], 404);
        }
        $invoiceRefund = Refund::where('invoice_id', $invoice_id)->where('status', 'pending')->first();
        if ($invoiceRefund) {
            if ($invoiceRefund->status == 'pending') {
                $refund = $invoiceRefund;
            }
            if ($invoiceRefund->amount == $invoice->amount_changable) {
                return response()->json(['msssage' => 'this invoice arleady refunded'], 404);
            } elseif ($invoiceRefund->amount + $request_refund['amount'] > $invoice->amount_changable) {
                return response()->json(['msssage' => "amount must be less than " . $invoice->amount_changable - $invoiceRefund->amount], 404);
            }
            $refund->update([
                'amount' => $request_refund['amount'] + $refund->amount
            ]);
        } else {
            $refund = Refund::create($request_refund);
        }
        $this->notification($refund->id, $invoice_id, $refund->profile_id);

        //admin notification
        $api = url("admin/refunds");
        $text = "New Refund Created from profile {$user->profileBusiness->company_name} ";
        $this->notificationServiceClass->adminNotification($refund->id, $refund->profile_id, $text, $api, 'refunds', $user->id);

        return response()->json(['message' => 'success']);
    }

    public function refund_summury($invoice_id)
    {
        $user = auth()->user();

        $activity = activity::where('invoice_id', $invoice_id)
            ->where('profile_id', $user->profile_business_id)->firstOrFail();

        $charge = $this->stripeService->stripeChargeRetrieve($activity->charge_id);

        return response()->json([
            'Customer Paid' => $charge->amount / 100,
            'Vendor Received' => $charge->balance_transaction->net / 100
        ]);
    }

    public function delete($id)
    {
        $user = auth()->user();
        Refund::where('profile_id', $user->profile_business_id)
            ->where('status', 'pending')
            ->findOrFail($id)->delete();
        return response()->json(['message' => 'success']);
    }



    ///////// Refund admin

    public function confirm_refund($id)
    {
        $admin = auth()->guard('admin')->user();


        $refund = Refund::findOrFail($id);
        $invoice = Invoice::select('id', 'refund_amount', 'invoice_display_value')->find($refund->invoice_id);

        $activities = activity::where('invoice_id', $refund->invoice_id)->get();
        $wallet =  Wallet::where('profile_id', $refund->profile_id)->first();

        $refund_amount_with_currency_profile =  $this->convertCurrencyServiceClass->convertFromDisplayValueToInvoceValue($refund->profile_id, $refund->invoice->currency_id, $refund->amount);
        if ($wallet->total_balance < $refund_amount_with_currency_profile) {
            return response()->json(['message' => 'this profile can not do  refund From Wallet'], 404);
        }

        // $charges = [];
        if ($refund->status == 'Refunded') {
            return response()->json(['message' => 'this invoice arleady refunded '], 404);
        } else {
            $refund_amount_aed = $this->convertCurrencyServiceClass->convartCurrencyInvoiceToAED($refund->invoice->currency->short_currency, $refund->amount);
            try {
                // $amountRefund = 0;
                foreach ($activities as $index => $activity) {

                    $charge = $this->stripeService->stripeChargeRetrieve($activity->charge_id);
                    if ($charge->amount_refunded < $charge->amount) {
                        if ($refund_amount_aed > (($charge->amount - $charge->amount_refunded) / 100)) {
                            $amountRefund = $this->stripeService->stripeRefund($charge->id);
                            $refund_amount_aed -= $amountRefund->amount;
                        } else if ($refund_amount_aed > 0) {
                            $amountRefund = $this->stripeService->stripeRefund($charge->id, $refund_amount_aed);
                            $refund_amount_aed -= $amountRefund->amount;
                        }
                    } else if (count($activities) - 1 == $index) {
                        return response()->json(['message' => 'this invoice cat not refunded'], 404);
                    }
                }


                $refund_amount_with_currency_profile =  $this->convertCurrencyServiceClass->convertFromDisplayValueToInvoceValue($refund->profile_id, $refund->invoice->currency_id, $refund->amount);
                $refund_amount_with_dollers = $this->convertCurrencyServiceClass->convartCurrencyWalletToUSA($wallet->profile->country->short_currency, $refund->amount);

                DB::beginTransaction();

                AccountStatement::create([
                    'profile_id' => $refund->profile_id,
                    'Description' => 'Refund',
                    'Debit' => $refund_amount_with_currency_profile,
                    'Credit' => null,
                    'Balance' => $wallet->total_balance - $refund_amount_with_currency_profile,
                    'reference_number' => $refund->invoice_id
                ]);


                $wallet->update([
                    'total_balance' => $wallet->total_balance - $refund_amount_with_currency_profile,
                    'total_balance_doller' => $wallet->total_balance_doller - $refund_amount_with_dollers
                ]);

                $refund->update([
                    'status' => 'Refunded',
                ]);

                if ($invoice->refund_amount + $refund->amount == $invoice->invoice_display_value) {
                    // dump($invoice->refund_amount);
                    // dump($refund->amount);
                    // dump($invoice->invoice_display_value);
                    // dd('dd');
                    $invoice->update([
                        'refund_amount' => $invoice->refund_amount + $refund->amount,
                        'status' => 'refunded'
                    ]);
                } else {
                    // dump($invoice->refund_amount);
                    // dump($refund->amount);
                    // dump($invoice->invoice_display_value);
                    // dd('asasa');
                    $invoice->update([
                        'refund_amount' => $invoice->refund_amount + $refund->amount,
                    ]);
                }

                DB::commit();

                $api = url("admin/refunds");
                $text = "Refund {$refund->id} updated for profile {$refund->invoice->profile->company_name} from admin {$admin->name} ";
                $this->notificationServiceClass->adminNotification($refund->id, $refund->profile_id, $text, $api, 'refunds', $admin->id);

                return response()->json(['message' => 'success']);
            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()], 404);
            }
        }
    }


    public function index_admin()
    {
        $user = auth()->user();
        $refunds = Refund::with(['invoice' => function ($q) {
            $q->with('currency');
        }])->get();
        return response()->json(['data' => $refunds]);
    }


    public function delete_admin($id)
    {
        Refund::findOrFail($id)->delete();
        return response()->json(['message' => 'success']);
    }

    public function notification($refund_id, $invoice_id, $refund_profile_id)
    {

        $domain = URL::to('/');
        $api = "$domain/api/refund/show/$refund_id";
        $invoice = Invoice::select('profile_business_id', 'customer_name')->find($invoice_id);
        $text = "refund " . $refund_id . " Transfered to customer " . $invoice->customer_name;
        $column = 'notification_refund_transfered';

        $this->notificationServiceClass->notification($refund_id, $refund_profile_id, $text, $api, $column);
        $this->sendMailService->send_email_refund($refund_id, $invoice->profile_business_id, $invoice->customer_name);
    }
}
