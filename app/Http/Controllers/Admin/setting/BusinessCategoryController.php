<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessCategoryController extends Controller
{
    function index()
    {
        $Categories = Category::get();
        return response()->json([
            'data' => $Categories
        ]);
    }

    function store(Request $request)
    {
        $validateData = [
            'name_en' => 'required|string|max:255|unique:categories,name_en',
            'name_ar' => 'required|string|max:255|unique:categories,name_ar',
            'default' => 'nullable|boolean',
        ];

        $requestData = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'default' => $request->default ? true : false,
        ];

        $data = Validator::make($requestData, $validateData);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }
        Category::create($requestData);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }
    public function show($id)
    {
        $category = Category::find($id);
        return $category ? response()->json(['data' => $category]) : response()->json(['message' => 'category is not found']);
    }

    function update(Request $request, $id)
    {
        $categoryUpdate = Category::find($id);
        if ($categoryUpdate) {
            $requestData = [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'default' => $request->default ? true : false,
            ];

            $validateData = [
                'name_en' => 'required|string|max:255|unique:categories,name_en,' . $id,
                'name_ar' => 'required|string|max:255|unique:categories,name_ar,' . $id,
                'default' => 'boolean|nullable',
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
            'message' => 'The Business Profile Category Is not found'
        ], 404);
    }
    function delete($id)
    {
        $categoryDelete = Category::findOrFail($id);

        if ($categoryDelete) {

            $categoryDelete->delete();

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'The Business Profile Category Is not found'
        ]);
    }
}
