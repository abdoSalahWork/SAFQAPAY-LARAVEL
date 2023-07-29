<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\File;


class ProductService
{

    public function store()
    {
        $user = auth()->user();

        $requestData = $this->requestData();

        $requestData['product_image'] = request()->product_image ?
            getdate()['year'] . getdate()['yday'] . time() . '.' . request()->product_image->extension()
            : null;


        Product::create($requestData);
        if ($requestData['product_image']) {
            request()->file('product_image')->storeAs("public/images/product/" . $user->profile_business_id, $requestData['product_image']);
        }
    }


    public function update($product_id)
    {
        $user = auth()->user();
        $productUpdate = Product::findOrFail($product_id);

        $pathOldImage  = storage_path('app/public/images/product/' . $productUpdate->manager_user_id . '/' . $productUpdate->product_image);

        $requestData = $this->requestData();

        $requestData['product_image'] = request()->product_image
            ? getdate()['year'] . getdate()['yday'] . time() . '.' . request()->product_image->extension()
            : $productUpdate->product_image;

        $productUpdate->update($requestData);

        if (request()->product_image) {
            request()->file('product_image')->storeAs("public/images/product/" . $user->profile_business_id, $requestData['product_image']);
            if (File::exists($pathOldImage)) {
                unlink($pathOldImage);
            }
        }
    }

    
    public function requestData()
    {
        $user = auth()->user();

        $requestData = [
            'manager_user_id' => $user->id,
            'profile_business_id' => $user->profile_business_id,
            'category_id' => request()->category_id,
            'name_en' => request()->name_en, // unique yes or No
            'name_ar' => request()->name_ar,
            'description_en' => request()->description_en,
            'description_ar' => request()->description_ar,
            'weight' => request()->weight,
            'height' => request()->height,
            'width' => request()->width,
            'length' => request()->length,
            'quantity' => request()->quantity,
            'price' => request()->price,
            'product_image' => request()->product_image, // ratio=3/2
            'currency_id' => request()->currency_id,
            'is_stockable' => request()->is_stockable ? true : false,
            'disable_product_on_sold' => request()->disable_product_on_sold ? true : false,
            'is_active' => request()->is_active ? true : false,
            'is_shipping_product' => request()->is_shipping_product ? true : false,
            'in_store' => request()->in_store ? true : false,
        ];
        return $requestData;
    }
}
