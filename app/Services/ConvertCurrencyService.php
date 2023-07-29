<?php

namespace App\Services;


use App\Models\ProfileBusiness;
use App\Models\setting\Country;
use AmrShawky\LaravelCurrency\Facade\Currency;

class ConvertCurrencyService
{
    // convertCurrency
    function convertCurrency($fromCurrency, $toCurrency, $amountValue)
    {
        $amountConvertValue = Currency::convert()
            ->from($fromCurrency)
            ->to($toCurrency)
            ->amount($amountValue)
            ->get();
        return $amountConvertValue;
    }

    function convertFromDisplayValueToInvoceValue($profile_business_id, $currency_id, $invoice_display_value)
    {
        $getProfileUser = ProfileBusiness::with('country')->find($profile_business_id);
        $getCurrencyOfDispalyValue = Country::find($currency_id);

        $displayUserCurrency = $getCurrencyOfDispalyValue->short_currency;
        $profileBusinessCurrency = $getProfileUser->country->short_currency;

        return $this->convertCurrency($displayUserCurrency, $profileBusinessCurrency, $invoice_display_value);
    }

    function convertCurrencyAdmin($currency, $amount)
    {
        return $this->convertCurrency($currency, 'USD', $amount);
    }


    function convertCurrencyStripe($short_currency_stripe, $short_currency_profile, $amount)
    {
        return $this->convertCurrency($short_currency_stripe, $short_currency_profile, $amount);
    }

    public function convartCurrencyInvoiceToAED($short_currency_display_invoice, $amount)
    {
        return $this->convertCurrency($short_currency_display_invoice, 'AED', $amount);
    }
    public function convartCurrencyWalletToUSA($short_currency_profile, $amount)
    {
        return $this->convertCurrency($short_currency_profile, 'USD', $amount);
    }

    public function convertCurrency30cToProfile( $short_currency_profile)
    {
        return $this->convertCurrency('USD', $short_currency_profile, 0.30);
    }
}
