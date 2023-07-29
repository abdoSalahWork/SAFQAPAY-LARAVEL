<?php

namespace App\Http\Controllers;

use App\Models\PaymentInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentInformationController extends Controller
{
    public function show()
    {
        $paymentInformation = PaymentInformation::first();
        return response()->json([
            'data' => $paymentInformation
        ]);
    }

    public function update(Request $request)
    {
        $validateData = [
            'payment_key' => 'required',
            'payment_secret' => 'required',
        ];
        $requestData = [
            'payment_key' => $request->payment_key,
            'payment_secret' => $request->payment_secret,
        ];

        $data = Validator::make($requestData, $validateData);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }
        PaymentInformation::first()->update($requestData);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }
}
