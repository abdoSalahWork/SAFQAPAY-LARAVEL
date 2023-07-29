<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\AddressType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressTypeController extends Controller
{
    function index()
    {
        $addressType = AddressType::get();
        return response()->json([
            'data' => $addressType
        ]);
    }
    function store(Request $request)
    {
        $validateData = [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
        ];
        $requestData = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
        ];

        $data = Validator::make($requestData, $validateData);
        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        AddressType::create($requestData);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }
    public function show($id)
    {
        $addressType = AddressType::find($id);
        return $addressType ? response()->json(['data' => $addressType])
            : response()->json(['message' => 'addressType is not found'], 404);
    }
    function update(Request $request, $id)
    {
        $addressTypeUpdate = AddressType::find($id);

        if ($addressTypeUpdate) {
            $requestData = [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,

            ];
            $validateData = [
                'name_en' => 'required|string|max:255', // unique yes or No
                'name_ar' => 'required|string|max:255',

            ];
            $data = Validator::make($requestData, $validateData);
            if ($data->fails()) {
                return response()->json($data->errors(), 404);
            }

            $addressTypeUpdate->update($requestData);

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'This Recurring Interval Is not found'
        ], 404);
    }
    function delete($id)
    {
        $addressTypeDelete = AddressType::find($id);

        if ($addressTypeDelete) {

            $addressTypeDelete->delete();

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'This Recurring Interval Is not found'
        ], 404);
    }
}
