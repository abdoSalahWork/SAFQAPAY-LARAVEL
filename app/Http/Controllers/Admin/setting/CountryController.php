<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    function index()
    {
        $countries = Country::get();
        $imageUrl = url('image/country');
        return response()->json(['data' => $countries, 'imageUrl' => $imageUrl]);
    }

    function show($id)
    {
        $country = Country::find($id);
        $imageUrl = url('image/country');
        return $country ? response()->json(['data' => $country, 'imageUrl' => $imageUrl])
            : response()->json(['message' => 'country is not found'], 404);
    }
    function store(Request $request)
    {
        $validateData = [
            'name_en' => 'required|string|max:255|unique:countries,name_en',
            'name_ar' => 'required|string|max:255|unique:countries,name_ar',
            'code' => 'required|string|max:255|unique:countries,code',
            'nationality_en' => "required|string|max:255|unique:countries,nationality_en",
            'nationality_ar' => "required|string|max:255|unique:countries,nationality_ar",
            'currency' => 'required|string|max:255|unique:countries,currency',
            'short_currency' => 'required|string|max:255|unique:countries,short_currency',
            'short_name' => 'required|string|max:255|unique:countries,short_name',
            'flag' => 'required|mimes:jpg,png,webp,jfif',
            'country_active' => 'boolean|nullable',
        ];

        $requestData = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'code' => $request->code,
            'nationality_en' => $request->nationality_en,
            'nationality_ar' => $request->nationality_ar,
            'currency' => $request->currency,
            'short_currency' => $request->short_currency,
            'short_name' => $request->short_name,
            'flag' => $request->flag,
            'country_active' => $request->country_active ? true : false,
        ];
        $data = Validator::make($requestData, $validateData);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        $requestData['flag'] = time() . '.' . $request->flag->extension();


        Country::create($requestData);

        $request->file('flag')->storeAs("public/images/admin/country", $requestData['flag']);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }

    function update(Request $request, $id)
    {
        $updateCountry = Country::findOrFail($id);
        $validateData = [
            'name_en' => "required|string|max:255|unique:countries,name_en,$id",
            'name_ar' => "required|string|max:255|unique:countries,name_ar,$id",
            'code' => "required|string|max:255|unique:countries,code,$id",
            'nationality_en' => "required|string|max:255|unique:countries,nationality_en,$id",
            'nationality_ar' => "required|string|max:255|unique:countries,nationality_ar,$id",
            'currency' => "required|string|max:255|unique:countries,currency,$id",
            'short_currency' => "required|string|max:255|unique:countries,short_currency,$id",
            'short_name' => "required|string|max:255|unique:countries,short_name,$id",
            'flag' => 'nullable|mimes:jpg,png,webp,jfif',
            'country_active' => 'boolean|nullable',
        ];

        $requestData = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'code' => $request->code,
            'nationality_en' => $request->nationality_en,
            'nationality_ar' => $request->nationality_ar,
            'currency' => $request->currency,
            'short_currency' => $request->short_currency,
            'short_name' => $request->short_name,
            'flag' => $request->flag,
            'country_active' => $request->country_active ? true : false,
        ];

        $data = Validator::make($requestData, $validateData);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }
        $pathOldImage = storage_path("app/public/images/admin/country/" . $updateCountry['flag']);

        $requestData['flag'] =  $request->flag ? time() . '.' . $request->flag->extension() : $updateCountry['flag'];

        $updateCountry->update($requestData);

        if ($request->flag) {
            $request->file('flag')->storeAs("public/images/admin/country", $requestData['flag']);

            if (File::exists($pathOldImage)) {
                unlink($pathOldImage);
            }
        }
        return response()->json([
            'message' => 'sucsess'
        ]);
    }
    function delete($id)
    {
        $deleteCountry = Country::findOrFail($id);

        $pathOldImage  = storage_path("app/public/images/admin/country/" . $deleteCountry['flag']);
        $deleteCountry->delete();
        if (File::exists($pathOldImage)) {
            unlink($pathOldImage);
        }
        return response()->json([
            'message' => 'sucsess'
        ]);
    }
}
