<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class InvoiceRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function normalInvoice(): array
    {
        $valiInvoice = Validator::make(request()->all(), [

            'recurring_interval_id' => 'required|integer|exists:recurring_intervals,id',

            'is_open_invoice' =>  'required',

            'discount_type' =>  'nullable|boolean',
            'discount_value' => 'nullable|string',

            'min_invoice' => 'required_if:is_open_invoice,==,1|integer|nullable',
            // 'max_invoice' => 'required_if:is_open_invoice,==,1|integer|nullable|gt:min_invoice',


            'recurring_start_date' => 'required_if:recurring_interval_id,==,2|required_if:recurring_interval_id,==,3|date_format:Y-m-d|before:recurring_end_date',
            'recurring_end_date' => 'required_if:recurring_interval_id,==,2|required_if:recurring_interval_id,==,3|date_format:Y-m-d|before:expiry_date',

            'expiry_date' => 'required|date_format:Y-m-d H:i|after:now',

            'attach_file' => 'nullable|image|mimes:jpg,png,webp,jfif|max:2048',

            'terms_and_conditions' => 'string|max:255|nullable',
            'remind_after' => 'required|integer',
            'prductItems' => 'required_if:is_open_invoice,==,0'


        ]);
        if ($valiInvoice->fails()) {
            return ['message' => $valiInvoice->errors()];
        } else if (request()->prductItems)
            return $this->valiInvoiceItems(request()->prductItems);
        else {
            return ['message' => 'true'];
        }
    }

    public function valiInvoiceItems(array $prductItems): array
    {
        foreach ($prductItems as $prductItem) {
            $validateInvoceItem = Validator::make($prductItem, [
                "product_name" => 'required|string|max:255',
                "product_quantity"  => 'required|integer|min:1',
                "product_price"  => 'required|integer|min:1',
            ]);

            if ($validateInvoceItem->fails()) {
                return ['message' => $validateInvoceItem->errors()];
            }
        }
        return ['message' => 'true'];
    }
    public function Invoice()
    {
        $Invoice = Validator::make(request()->all(), [
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
        ]);

        if ($Invoice->fails()) {
            return ['message' => $Invoice->errors()];
        }

        if (request()->requestUri == "/api/invoice/store" or request()->requestUri == "/api/invoice/update/" . request()->route()->id) {
            return $this->normalInvoice();
        }
        return ['message' => 'true'];
    }


    public function rules(): array
    {
        return $this->Invoice();
    }
}
