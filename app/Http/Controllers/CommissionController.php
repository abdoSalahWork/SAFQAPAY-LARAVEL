<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommissionController extends Controller
{
    public function show()
    {
        $commission = Commission::first();
        return response()->json([
            'data' => $commission
        ]);
    }

    public function update(Request $request)
    {
        $validateData = [
            'safqa_commission' => 'required|numeric',
            'payment_commission' => 'required|numeric',
        ];
        $requestData = [
            'safqa_commission' => $request->safqa_commission,
            'payment_commission' => $request->payment_commission,

        ];

        $data = Validator::make($requestData, $validateData);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }
        Commission::first()->update($requestData);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }
}
