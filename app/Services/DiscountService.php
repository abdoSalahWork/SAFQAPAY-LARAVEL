<?php

namespace App\Services;



class DiscountService
{

    function discount($invoice_vlalue, $discount_type, $discount_value)
    {
        if ($discount_type == 0)
            $invoice_vlalue = $invoice_vlalue - $discount_value;
        else
            $invoice_vlalue = $invoice_vlalue - ($discount_value / 100 * $invoice_vlalue);

        return $invoice_vlalue;
    }
}
