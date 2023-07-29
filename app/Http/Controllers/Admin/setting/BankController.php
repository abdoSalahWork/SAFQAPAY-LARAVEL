<?php

namespace App\Http\Controllers\Admin\setting;

use App\Events\AdminNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\setting\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BankController extends Controller
{
    function index()
    {
        $banks = Bank::with('country')->get();
        return response()->json([
            'data' => $banks
        ]);
    }
    function store(Request $request)
    {
        $validateData = [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'country_id' => 'required|integer|exists:countries,id',
        ];
        $requestData = [
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'is_active' => $request->is_active ? true : false,
            'country_id' => $request->country_id,
        ];

        $data = Validator::make($requestData, $validateData);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }
        Bank::create($requestData);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }

    public function show($id)
    {
        $bank = Bank::with('country')->find($id);
        return $bank ? response()->json(['data' => $bank]) : response()->json(['message' => 'bank is not found'], 404);
    }

    function update(Request $request, $id)
    {

        $bankUpdate = Bank::find($id);
        if ($bankUpdate) {
            $validateData = [
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'is_active' => 'required|boolean',
                'country_id' => 'required|integer|exists:countries,id',
            ];
            $requestData = [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'is_active' => $request->is_active ? true : false,
                'country_id' => $request->country_id,
            ];

            $data = Validator::make($requestData, $validateData);

            if ($data->fails()) {
                return response()->json($data->errors(), 404);
            }

            $bankUpdate->update($requestData);

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json(['message' => "this Bank is not found"], 404);
    }
    function delete($id)
    {
        $bankDelete = Bank::findOrFail($id);

        if ($bankDelete) {

            $bankDelete->delete();

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => "this Bank is not found"
        ], 404);
    }
}
