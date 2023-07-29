<?php

namespace App\Services;

use App\Models\setting\Country;

class PercentagePaymentService
{
    private $convertCurrencyServiceClass;

    public function __construct(ConvertCurrencyService $convertCurrencyService)
    {
        $this->convertCurrencyServiceClass = $convertCurrencyService;
    }

    public function percentagePayment($amount , $currency_id)
    {
        $getCurrencyOfDispalyValue = Country::find($currency_id);

        $amount  = $amount * 0.05;

        $cent_30 = $this->convertCurrencyServiceClass->convertCurrency30cToProfile($getCurrencyOfDispalyValue->short_currency);
        return $amount + $cent_30;
    }
}
