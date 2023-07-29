<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class PaymentInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $validtion = Validator::make(request()->all(), [
            'customer_name' => 'required|string|max:20',
            'civil_id' => 'string|max:255|nullable',
            'customer_mobile' => 'string|max:20|required',
            'customer_email' =>  'email|nullable',
            'comment' => 'string|max:255|nullable',
        ]);
        if ($validtion->fails()) {
            return ['message' => $validtion->errors()];
        }

        return [];
    }
}
