<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ApiKey;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProfileBusiness;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{

    private $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    function index(Request $request)
    {
        $profile = check_user($request->header('profile'));
        if ($profile) {

        $products = Product::where('profile_business_id', $profile->id)->with('category')->get();
        $urlImage = url("image/product/$profile->id");
        return response()->json([
            'data' => $products,
            'urlImage' => $urlImage
        ]);
    }
    return response()->json(['message' => 'Please Choose Profile'], 404);
    }
    function show($product_id)
    {
        $user = auth()->user();
        $product = Product::with('category')->with('currency')->find($product_id);
        if ($product) {
            if ($product->profile_business_id == $user->profile_business_id) {
                $product->urlImage = url("image/product/$user->profile_business_id");
                return response()->json([
                    "data" => $product,
                ]);
            }
        }
        return response()->json([
            'message' => 'product not found',
        ], 404);
    }
    function store(StoreProductRequest $request)
    {
        if (isset($request->rules()['message'])) {
            return response()->json($request->rules()['message'], 404);
        }

        $this->productService->store();

        return response()->json([
            'message' => 'sucsess'
        ]);
    }
    function update(UpdateProductRequest $request, $product_id)
    {
        $user = auth()->user();
        $productUpdate = Product::findOrFail($product_id);

        if (isset($request->rules()['message'])) {
            return response()->json($request->rules()['message'], 404);
        }

        if ($user->profile_business_id == $productUpdate->profile_business_id) {
            $this->productService->update($product_id);
            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        else{
            return response()->json([
                'message' => 'you can not update this product'
            ],404);
        }
    }
    function delete($product_id)
    {
        $user = auth()->user();
        $productDelete = Product::findOrFail($product_id);

        if ($user->profile_business_id == $productDelete->profile_business_id) {

            $pathOldImage  = storage_path('app/public/images/product/' . $productDelete->profile_business_id . '/' . $productDelete->product_image);
            $productDelete->delete();
            if ($productDelete->product_image) {
                if (File::exists($pathOldImage)) {
                    unlink($pathOldImage);
                }
            }

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'You do not have permission to access this product'
        ], 404);
    }
}
