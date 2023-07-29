<?php

namespace App\Http\Controllers;

use App\Models\activity;
use App\Models\Invoice\Invoice;
use App\Models\ProfileBusiness;
use App\Models\Wallet;
use App\Services\ConvertCurrencyService;
use App\Services\StripeService;
use Illuminate\Support\Carbon;

class HomePageController extends Controller
{
    private $StripeService, $convertCurrencyServiceClass;
    public function __construct(
        StripeService $StripeService,
        ConvertCurrencyService $convertCurrencyService,

    ) {
        $this->convertCurrencyServiceClass = $convertCurrencyService;
        $this->StripeService = $StripeService;
    }
    public function homePage()
    {

        return response()->json([
            'sales_invoice' => $this->salesInvoice(),
            'all_invoices' => $this->invoices(),
            'payment_invoices' => $this->invoice_type('payment_invoice'),
            'product_invoice' => $this->invoice_type('product_invoice'),
            'normal_invoices' => $this->invoice_type('invoice'),
            'wallet_profile' => $this->walletProfile(),
            'transaction_count' => $this->transaction_count(),
            'transaction_value' => $this->transaction_value(),
            'payment_methods' => $this->paymentMethod()
        ]);
    }
    // product_invoice , payment_invoice
    public function invoice_type($value)
    {
        $user = auth()->user();

        $invoices = Invoice::where('profile_business_id', $user->profile_business_id)
            ->where('invoice_type', $value)
            ->select('id', 'status', 'created_at')
            ->whereBetween(
                'created_at',
                [Carbon::now()->subYear(), Carbon::now()]
            )
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('m');
            });
        return $this->InvoiceCount($invoices);
    }

    public function invoices()
    {
        $user = auth()->user();

        $invoices = Invoice::where('profile_business_id', $user->profile_business_id)
            ->select('id', 'status', 'created_at')
            ->whereBetween(
                'created_at',
                [Carbon::now()->subYear(), Carbon::now()]
            )
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('m');
            });
        return $this->InvoiceCount($invoices);
    }

    private function InvoiceCount($invoices)
    {
        $invoiceMonthCount = [];
        $invoiceMonthCountPaid = [];
        $invoiceArr = [];

        $index = 0;
        foreach ($invoices as $key => $value) {
            $invoiceMonthCount[(int)$key] = count($value);

            foreach ($value as $invoice) {
                if ($invoice->status == 'paid') {
                    // $invoiceMonthCountPaid[(int)$key] = $invoice->where('status', 'paid')->whereMonth('created_at',$key)->get()->count();
                    $invoiceMonthCountPaid[(int)$key] = ++$index;
                }
            }
        }
        $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $start = (int)Carbon::parse()->now()->format('m');

        for ($i = 1; $i <= 12; $i++) {

            if ($start < 1) {
                $start = 12;
            }
            if (!empty($invoiceMonthCount[$start])) {
                $invoiceArr[$start]['count'] = $invoiceMonthCount[$start];
            } else {
                $invoiceArr[$start]['count'] = 0;
            }
            if (!empty($invoiceMonthCountPaid[$start])) {
                $invoiceArr[$start]['paidCount'] = $invoiceMonthCountPaid[$start];
            } else {
                $invoiceArr[$start]['paidCount'] = 0;
            }
            $invoiceArr[$start]['month'] = $month[$start - 1];

            $start--;
        }
        return array_values($invoiceArr);
    }

    public function transaction_count()
    {
        $user = auth()->user();

        $currentMonth = date('m');
        $current_month = activity::where('profile_id', $user->profile_business_id)->whereRaw('MONTH(created_at) = ?', [$currentMonth])->count();
        $all = activity::where('profile_id', $user->profile_business_id)->count();
        return ['currentMonth' => $current_month, 'all' => $all];
    }

    public function transaction_value()
    {
        $user = auth()->user();

        $current_amount = 0;
        $current_month_amount = 0;

        $activities = activity::where('profile_id', $user->profile_business_id)->select('profile_id', 'charge_id', 'created_at')->get();
        foreach ($activities as $activity) {
            $charge = $this->StripeService->stripeChargeRetrieve($activity->charge_id);
            $current_amount += $charge->amount;
            if ($activity->created_at->format('m') == date('m')) {
                $current_month_amount += $charge->amount;
            }
        }
        return [
            'current_month_amount' => $this->convertCurrencyServiceClass->convertCurrencyStripe('AED',$user->profileBusiness->country->short_currency, $current_month_amount / 100),
            'current_amount' => $this->convertCurrencyServiceClass->convertCurrencyStripe('AED',$user->profileBusiness->country->short_currency, $current_amount / 100)
        ];
    }

    public function salesInvoice()
    {
        $user = auth()->user();

        $getAllUsers = ProfileBusiness::where('id', $user->profile_business_id)->with('managerUsers.invoice')->first();
        $box = [];
        foreach ($getAllUsers->managerUsers as $sale) {
            $box[] = [
                'sales_person' => $sale->full_name,
                'amount_collected' => $sale->invoice->where('status', 'paid')->sum('invoice_value'),
                'count_invoices' => $sale->invoice->where('status', 'paid')->count(),
            ];
        }
        return $box;
    }

    public function walletProfile()
    {

        $user = auth()->user();

        $wallet = Wallet::where('profile_id', $user->profile_business_id)->first();

        return [
            'total_balance' => $wallet->total_balance,
            'awating_transfer' => $wallet->awating_transfer,
        ];
    }

    public function paymentMethod()
    {
        $user = auth()->user();
        $payemnts = activity::where('profile_id', $user->profile_business_id)->get()->groupBy('typeCard');
        return  $payemnts;
    }
}
