<?php

namespace App\Services;

use App\Models\AccountStatement;
use App\Models\Commission;
use App\Models\Invoice\Invoice;
use App\Models\ProfileBusiness;
use App\Models\Wallet;
use App\Models\WalletAdmin;
use Illuminate\Support\Facades\DB;

class WalletService
{
    private $convertCurrencyServiceClass;

    public function __construct(
        ConvertCurrencyService $convertCurrencyService,
    ) {
        $this->convertCurrencyServiceClass = $convertCurrencyService;
    }
    function wallet($profile_id, $net, $amount, $retrieve, $invoice_id)
    {

        $wallet = Wallet::where('profile_id', $profile_id)->first();
        $profile = ProfileBusiness::findOrFail($profile_id);
        // $retrieve->balance_transaction->currency
        // dd($profile->country->short_currency);
        $net =  $this->convertCurrencyServiceClass->convertCurrencyStripe('AED', $profile->country->short_currency, $net);

        if (!$wallet) {
            return null;
        }
        // $net = $net / 100;
        $commission = Commission::first();

        $final_net = $net - $commission->safqa_commission / 100 * $net;
        $safqaFee = $net - $final_net;


        $walletAdmin = WalletAdmin::find(1);
        $walletAdmin->update(['safqa_wallet' => $safqaFee + $walletAdmin->safqa_wallet]);

        $amount_with_currency_profile =  $this->convertCurrencyServiceClass->convertCurrencyStripe('AED', $profile->country->short_currency, $amount);
        $final_net_dollers =  $this->convertCurrencyServiceClass->convartCurrencyWalletToUSA($profile->country->short_currency, $final_net);

        $total_fees = ($amount_with_currency_profile - $final_net) + $safqaFee;


        $invoice = Invoice::select('invoice_type')->where('profile_business_id', $profile_id)->find($invoice_id);
        if ($invoice->invoice_type == 'invoice') {
            $description = 'Invoice Paid';
        } elseif ($invoice->invoice_type == 'product_invoice') {
            $description = 'Product Paid';
        } elseif ($invoice->invoice_type == 'payment_invoice') {
            $description = 'Payment Paid';
        }
        DB::beginTransaction();

        AccountStatement::create([
            'profile_id' => $profile_id,
            'Description' => $description,
            'Debit' => null,
            'Credit' => $amount_with_currency_profile,
            'Balance' => $wallet->total_balance + $amount_with_currency_profile,
            'reference_number' => $invoice_id
        ]);


        $wallet->total_balance += $final_net;
        $wallet->update([
            'total_balance' => $wallet->total_balance,
            'total_balance_doller' => $wallet->total_balance_doller + $final_net_dollers
        ]);

        AccountStatement::create([
            'profile_id' => $profile_id,
            'reference_number' => $invoice_id,
            'Description' => 'Fees',
            'Debit' => $total_fees,
            'Credit' => null,
            'Balance' => $wallet->total_balance
        ]);
        DB::commit();

        return $wallet->id;
    }

    public function walletSafqa($amount, $currency)
    {
        $amount_usd = $this->convertCurrencyServiceClass->convertCurrencyAdmin($currency, $amount);
        $walletAdmin =  WalletAdmin::first();
        $walletAdmin->update([
            'safqa_wallet' => $walletAdmin->safqa_wallet + $amount_usd
        ]);
    }
}
