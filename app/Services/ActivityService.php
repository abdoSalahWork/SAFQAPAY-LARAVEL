<?php

namespace App\Services;

use App\Models\activity;
use Illuminate\Support\Facades\Validator;

class ActivityService
{
    private $convertCurrencyServiceClass;
    function __construct(
        ConvertCurrencyService $convertCurrencyService,
    )
    {
        $this->convertCurrencyServiceClass = $convertCurrencyService;
    }

    function activity($invoice, $wallet_id, $card_name, $charge_id, $typeCard,$transaction_value)
    {
        $transaction_value_doller = $this->convertCurrencyServiceClass->convartCurrencyWalletToUSA($invoice->currency->short_currency, $transaction_value);

        activity::create([
            'profile_id' => $invoice->profile_business_id,
            'wallet_id' => $wallet_id,
            'card_name' => $card_name,
            'invoice_id' => $invoice->id,
            'charge_id' => $charge_id,
            'typeCard' => $typeCard,
            'transaction_value'=> $transaction_value,
            'transaction_value_doller'=> $transaction_value_doller,

        ]);
        return response()->json(['message' => 'success']);
    }
}
