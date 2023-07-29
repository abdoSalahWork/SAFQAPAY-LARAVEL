<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UpdateProductRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $productUpdate = Product::findOrFail(request()->route()->id);

        $user = auth()->user();
        $validtion = Validator::make(request()->all(), [
            'category_id' => "required|exists:product_categories,id,is_active,1,profile_business_id,$user->profile_business_id",
            'name_en' => "required|string|max:255|unique:products,name_en,$productUpdate->id,id,profile_business_id,$user->profile_business_id", // unique yes or No
            'name_ar' => "required|string|max:255|unique:products,name_ar,$productUpdate->id,id,profile_business_id,$user->profile_business_id",
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'weight' => 'nullable|string',
            'height' => 'nullable|string',
            'width' => 'nullable|string',
            'length' => 'nullable|string',

            'quantity' => 'required|integer',
            'price' => 'required|integer',

            'is_stockable' => 'required|boolean',
            'currency_id' => 'required|integer',
            'disable_product_on_sold' => 'required|boolean',
            'is_active' => 'required|boolean',
            'is_shipping_product' => 'nullable|boolean',
            'in_store' => 'required|boolean',

            'product_image' => 'nullable|image|mimes:jpg,png,webp',

        ]);
        if ($validtion->fails()) {
            return ['message' => $validtion->errors()];
        }
        return [];
    }
}
