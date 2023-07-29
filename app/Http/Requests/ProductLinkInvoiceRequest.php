<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ProductLinkInvoiceRequest extends FormRequest
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
            'customer_mobile' => 'string|max:20|nullable',
            'customer_email' =>  'email|nullable',
            'comment' => 'string|max:255|nullable',
            'prductItems'=> 'required|array',
            'prductItems.*.product_id'=> 'required|integer|max:255|exists:products,id',
            'prductItems.*.product_quantity'=> 'required|integer|min:1',
        ]);
        if ($validtion->fails()) {
            return ['message' => $validtion->errors()];
        }

        return [];
    }
}
