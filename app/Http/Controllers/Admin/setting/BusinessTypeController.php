<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\BusinessType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BusinessTypeController extends Controller
{
    function index()
    {
        $businessTypes = BusinessType::get();
        // $imageUrl = storage_path("app/public/images/admin/business_type/");
        $imageUrl = url("image/businessType");

        return response()->json([
            'data' => $businessTypes,
            'imageUrl' => $imageUrl
        ]);
    }
    function store(Request $request)
    {
        $validateData = [
            'name_en' => 'required|string|max:255|unique:business_types,name_en',
            'name_ar' => 'required|string|max:255|unique:business_types,name_ar',
            'business_logo' => 'required|image|mimes:jpg,png,webp',
            'default' => 'nullable|boolean',
        ];
        $requestData = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'business_logo' => $request->business_logo,
            'default' => $request->default ? true : false,
        ];

        $data = Validator::make($requestData, $validateData);
        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }


        $requestData['business_logo'] = time() . '.' . $request->business_logo->extension();

        BusinessType::create($requestData);

        $request->file('business_logo')->storeAs("public/images/admin/business_type", $requestData['business_logo']);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }

    public function show($id)
    {
        $businessType = BusinessType::find($id);
        return $businessType ? response()->json(['data' => $businessType]) : response()->json(['message' => 'businessType is not found', 404]);
    }

    function update(Request $request, $id)
    {
        
        $updateBusinessType = BusinessType::find($id);
        $pathOldImage = storage_path("app/public/images/admin/business_type/" . $updateBusinessType['business_logo']);

        if ($updateBusinessType) {
            $validateData = [
                'name_en' => 'required|string|max:255|unique:business_types,name_en,' . $id,
                'name_ar' => 'required|string|max:255|unique:business_types,name_ar,' . $id,
                'business_logo' => 'nullable|image|mimes:jpg,png,webp',
                'default' => 'nullable|boolean',
            ];
            $requestData = [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'business_logo' => $request->business_logo,
                'default' => $request->default ? true : false,
            ];

            $data = Validator::make($requestData, $validateData);

            if ($data->fails()) {
                return response()->json($data->errors(), 404);
            }

            if ($request->business_logo) {
                $requestData['business_logo'] = time() . '.' . $request->business_logo->extension();
            } else {
                $requestData['business_logo'] =  $updateBusinessType['business_logo'];
            }

            $updateBusinessType->update($requestData);

            if ($request->business_logo) {
                $request->file('business_logo')->storeAs("public/images/admin/business_type", $requestData['business_logo']);
                if (File::exists($pathOldImage)) {
                    unlink($pathOldImage);
                }
            }
            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'The Business Type Is not found'
        ], 404);
    }

    function delete($id)
    {
        $deleteBusinessType = BusinessType::findOrFail($id);
        if ($deleteBusinessType) {

            $pathOldImage  = storage_path("app/public/images/admin/business_type/" . $deleteBusinessType['business_logo']);

            $deleteBusinessType->delete();

            if (File::exists($pathOldImage)) {
                unlink($pathOldImage);
            }

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'The Business Type Is not found'
        ], 404);
    }
}
