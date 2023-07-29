<?php

namespace App\Http\Requests;

use App\Models\ProfileBusiness;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ProductStoreRequest extends FormRequest
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
    public function rules(): array
    {
        // $profile_id = SubscriptionTypes::where('type', request('subscription_types'))->select('id')->value('id');
        // $profile = ProfileBusiness::where(request()->route()->profile_company_name, 'company_name')->select('id')->first();
        $validtion = Validator::make(request()->all(), [
            'products' => 'required|array',
            "products.*.id" => "required|integer|exists:products,id",
            'products.*.quantity' => 'required|integer',
            'customer_name' => 'required|string',
            'customer_mobile' => 'required|string',
            'customer_email' => "nullable|email",
            "civil_id" => "nullable|string",
            "comment" => "nullable|string"
        ]);
        if ($validtion->fails()) {
            return ['message' => $validtion->errors()];
        }

        return [];
    }
}
