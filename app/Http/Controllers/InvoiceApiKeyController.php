<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceApiKeyController extends Controller
{
    function store(Request $request)
    {
        $InvoiceValidation = [
            'customer_name' => 'required|string|max:20',
            'send_invoice_option_id' => 'nullable|integer|exists:send_invoice_options,id',

            'currency_id' => 'nullable|integer|exists:countries,id',
            'language_id' => 'nullable|integer|exists:languages,id',
            'terms_and_conditions' => 'string|max:255|nullable',

            'customer_mobile_code_id' => 'string|max:20|required_if:send_invoice_option_id,==,1|exists:countries,id',
            'customer_mobile' => 'string|max:20|required_if:send_invoice_option_id,==,1',
            'customer_email' =>  'email|required_if:send_invoice_option_id,==,2',
            'customer_reference' =>  'string|max:20|nullable',
            'comments' => 'string|max:255|nullable',
        ];

        // if ($Invoice->fails()) {
        //     return ['message' => $Invoice->errors()];
        // }
    }
}
