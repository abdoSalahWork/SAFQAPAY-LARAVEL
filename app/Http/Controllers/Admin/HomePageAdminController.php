<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\activity;
use App\Models\Invoice\Invoice;
use App\Models\ProfileBusiness;
use App\Models\Wallet;
use App\Models\WalletAdmin;
use App\Services\ConvertCurrencyService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Profiler\Profile;

class HomePageAdminController extends Controller
{

    private $StripeService;
    private $convertCurrencyServiceClass;

    public function __construct(
        StripeService $StripeService,
        ConvertCurrencyService $convertCurrencyService,

    ) {
        $this->StripeService = $StripeService;
        $this->convertCurrencyServiceClass = $convertCurrencyService;
    }

    public function homePage()
    {
        return response()->json([
            'invoices' => $this->invoices(),
            'payment_invoices' => $this->invoice_type('payment_invoice'),
            'product_invoice' => $this->invoice_type('product_invoice'),
            'normal_invoices' => $this->invoice_type('invoice'),
            'wallet_safqa' => $this->walletSafqa(),
            'transaction_count' => $this->transaction_count(),
            'transaction_value' => $this->transaction_value(),
            'total_wallet_by_dollers' => $this->convertToDoller(),
        ]);
    }

    public function invoice_type($value)
    {
        $user = auth()->user();

        $invoices = Invoice::where('invoice_type', $value)
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
        $invoices = Invoice::select('id', 'status', 'created_at')
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

        foreach ($invoices as $key => $monthInvoice) {
            $invoiceMonthCount[(int)$key] = count($monthInvoice);
            $invoiceMonthCountPaid[(int)$key] = 0;
            foreach ($monthInvoice as $invoice) {
                if ($invoice->status == 'paid') {
                    ++$invoiceMonthCountPaid[(int)$key];
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
        $currentMonth = date('m');
        $currentyear = date('Y');

        $current_month = activity::whereRaw('YEAR(created_at) = ?', [$currentyear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])->count();
        $all = activity::count();
        return ['currentMonth' => $current_month, 'all' => $all];
    }

    public function transaction_value()
    {
        // $current_amount = 0;
        // $current_month_amount = 0;

        $currentMonth = date('m');
        $currentyear = date('Y');

        $activities = activity::select('profile_id', 'charge_id', 'created_at', 'transaction_value_doller')->sum('transaction_value_doller');
        $current_month = activity::whereRaw('YEAR(created_at) = ?', [$currentyear])
            ->whereRaw('MONTH(created_at) = ?', [$currentMonth])
            ->sum('transaction_value_doller');


        return [
            'current_month_amount' => $current_month,
            'current_amount' => $activities
        ];
    }

    public function walletSafqa()
    {
        $userWallets = Wallet::get();
        return ['total_balance' => $userWallets->sum('total_balance_doller'), 'awating_transfer' => $userWallets->sum('awating_transfer_doller')];
    }

    public function convertToDoller()
    {
        $profiles = ProfileBusiness::with('country')->with('wallet')->get();
        $total = 0;
        foreach ($profiles as $profile) {
            $total +=  $this->convertCurrencyServiceClass->convertCurrencyAdmin($profile->country->short_currency, $profile->wallet->total_balance);
        }
        return $total;
    }
}
