<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class StoreProductRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $user = auth()->user();
        $validtion = Validator::make(request()->all(), [
            'category_id' => "required|exists:product_categories,id,is_active,1,profile_business_id,$user->profile_business_id",
            'name_en' => "required|string|max:255|unique:products,name_en,NULL,id,profile_business_id,$user->profile_business_id", // unique yes or No
            'name_ar' => "required|string|max:255|unique:products,name_ar,NULL,id,profile_business_id,$user->profile_business_id",
            'description_en' => 'required|string',
            'description_ar' => 'required|string',

            'quantity' => 'required|integer',
            'price' => 'required|integer',
            'currency_id' => 'required|integer',
            'is_stockable' => 'required|boolean',
            'disable_product_on_sold' => 'required|boolean',
            'is_active' => 'required|boolean',
            'is_shipping_product' => 'nullable|boolean',
            'in_store' => 'required|boolean',

            'weight' => 'nullable|integer',
            'height' => 'nullable|integer',
            'width' => 'nullable|integer',
            'length' => 'nullable|integer',
            'product_image' => 'required|image|mimes:jpg,png,webp',

        ]);
        if ($validtion->fails()) {
            return ['message' => $validtion->errors()];
        }
        return [];
    }
}
