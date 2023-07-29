<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    function index(Request $request)
    {
        $profile = check_user($request->header('profile'));
        if ($profile) {

            $productCategories = ProductCategory::where('profile_business_id', $profile->id)->get();
            return response()->json([
                'data' => $productCategories
            ]);
        }
        return response()->json(['message' => 'Please Choose Profile'], 404);
    }
    function show($id)
    {
        $user = auth()->user();
        $productCategory = ProductCategory::find($id);
        if ($productCategory) {
            if ($productCategory->profile_business_id == $user->profile_business_id) {
                return response()->json(["data" => $productCategory]);
            }
            return response()->json([
                'message' => 'You do not have permission to access this Category'
            ], 404);
        }
        return response()->json([
            'message' => ' this Category isnot found'
        ], 404);
    }

    function store(Request $request)
    {
        $user = auth()->user();
        $validateData = [
            'name_en' => "required|string|max:255|unique:product_categories,name_en,NULL,id,profile_business_id,$user->profile_business_id", // unique yes or No
            'name_ar' => "required|string|max:255|unique:product_categories,name_ar,NULL,id,profile_business_id,$user->profile_business_id",
            'is_active' => 'required|boolean',
            'manager_user_id' => 'required|integer|exists:users,id',
            'profile_business_id' => 'required|integer|exists:profile_businesses,id',
        ];

        $requestData = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'is_active' => $request->is_active ? true : false,
            'manager_user_id' => $user->id,
            'profile_business_id' => $user->profile_business_id
        ];

        $data = Validator::make($requestData, $validateData);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        ProductCategory::create($requestData);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }
    function update(Request $request, $categoryId)
    {

        $user = auth()->user();
        $categoryUpdate = ProductCategory::findOrFail($categoryId);

        if ($user->profile_business_id == $categoryUpdate->profile_business_id) {
            $requestData = [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'is_active' => $request->is_active ? true : false,
                'manager_user_id' => $user->id,
                'profile_business_id' => $user->profile_business_id
            ];
            $validateData = [
                'name_en' => "required|string|max:255|unique:product_categories,name_en,$categoryId,id,profile_business_id,$user->profile_business_id",
                'name_ar' => "required|string|max:255|unique:product_categories,name_ar,$categoryId,id,profile_business_id,$user->profile_business_id",
                'is_active' => 'required|boolean',
                'manager_user_id' => 'required|integer|exists:users,id',
                'profile_business_id' => 'required|integer|exists:profile_businesses,id',

            ];

            $data = Validator::make($requestData, $validateData);
            if ($data->fails()) {
                return response()->json($data->errors(), 404);
            }

            $categoryUpdate->update($requestData);

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'You do not have permission to access this Category'
        ], 404);
    }

    function delete($categoryId)
    {
        $user = auth()->user();
        $categoryDelete = ProductCategory::findOrFail($categoryId);

        if ($user->profile_business_id == $categoryDelete->profile_business_id) {

            $categoryDelete->delete();

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'You do not have permission to access this Category'
        ], 404);
    }
}
