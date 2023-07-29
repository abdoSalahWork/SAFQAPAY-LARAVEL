<?php

namespace App\Http\Controllers;

use App\Models\AccountStatement;
use App\Models\Commission;
use App\Models\MoneyRequest;
use App\Models\Wallet;
use App\Models\WalletAdmin;
use App\Services\ConvertCurrencyService;
use App\Services\PercentagePaymentService;
use App\Services\StripeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    private $stripeService;
    private $convertCurrencyServiceClass;
    private $PercentagePaymentServiceClass;

    public function __construct(StripeService $stripeService, ConvertCurrencyService $convertCurrencyService, PercentagePaymentService $PercentagePaymentService)
    {
        $this->stripeService = $stripeService;
        $this->convertCurrencyServiceClass = $convertCurrencyService;
        $this->PercentagePaymentServiceClass = $PercentagePaymentService;
    }






    public function charge_wallet(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profileBusiness;
        $country = $profile->country;
        $data = Validator::make(['amount' => $request->amount], ['amount' => 'required']);
        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }
        $wallet = Wallet::where('profile_id', $profile->id)->firstOrFail();
        // $percentage = $this->PercentagePaymentServiceClass->percentagePayment($request->amount, $user->profileBusiness->country->id);
        // $request['amount'] = $request->amount + $percentage;

        try {

            $cardData = $this->stripeService->createTokenCard($request->all());
            $charge = $this->stripeService->stripeCharge($cardData->id, $request->amount, $country->short_currency);
            $retrieve = $this->stripeService->stripeChargeRetrieve($charge->id);
        } catch (Exception $e) {
            return response()->json(['message' => str_replace('"', '', $e->getMessage())], 404);
        }

        $net =  $this->convertCurrencyServiceClass->convertCurrencyStripe('AED', $profile->country->short_currency, ($retrieve->balance_transaction->net / 100));

        // $net = $retrieve->balance_transaction->net / 100;
        $commission = Commission::first();

        $final_net = $net - $commission->safqa_commission / 100 * $net;
        $safqaFee = $net - $final_net;

        $walletAdmin = WalletAdmin::find(1);
        $walletAdmin->update(['safqa_wallet' => $safqaFee + $walletAdmin->safqa_wallet]);

        $final_net_dollers =  $this->convertCurrencyServiceClass->convartCurrencyWalletToUSA($profile->country->short_currency, $final_net);

        $total_fees = ($request->amount - $final_net) + $safqaFee;



        $money_request = MoneyRequest::create([
            'profile_id' => $user->profile_business_id,
            'amount' => $final_net,
            'user_id' => $user->id,
            'type' => 'charge_wallet',
            'status' => 'paid',
        ]);

        AccountStatement::create([
            'profile_id' => $wallet->profile_id,
            'Description' => 'Charge Wallet',
            'Debit' => null,
            'Credit' => $request->amount,
            'Balance' => $wallet->total_balance + $request->amount,
            'reference_number' => $money_request->id
        ]);
        $wallet->update([
            'total_balance' => $wallet->total_balance + $final_net,
            'total_balance_doller' => $wallet->total_balance_doller + $final_net_dollers
        ]);
 
        AccountStatement::create([
            'profile_id' => $wallet->profile_id,
            'reference_number' => $money_request->id,
            'Description' => 'Fees',
            'Debit' => $total_fees,
            'Credit' => null,
            'Balance' => $wallet->total_balance
        ]);
        return response()->json(['message' => 'success']);
    }
}
