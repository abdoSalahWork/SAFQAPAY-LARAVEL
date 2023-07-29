<?php

namespace App\Http\Controllers;

use App\Models\CommissionPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommissionPaymentMethodController extends Controller
{
    public function index()
    {
        $commissionPaymentMethod = CommissionPaymentMethod::with('paymentMethod')->get();
        return response()->json([
            'data' => $commissionPaymentMethod
        ]);
    }
    public function store(Request $request)
    {

        $dataRequest = [
            'payment_method_id' => $request->payment_method_id,
            'body' => $request->body,
            'commission' => $request->commission,
        ];

        $dataValidate = [
            'payment_method_id' => 'required|exists:payment_methods,id',
            'body' => 'required',
            'commission' => 'nullable',
        ];
        $data = Validator::make($dataRequest, $dataValidate);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        CommissionPaymentMethod::updateOrCreate(
            ['payment_method_id' => $request->payment_method_id],
            $dataRequest
        );
        return response()->json([
            'message' => 'sucsess'
        ]);
    }

    public function show($id)
    {
        $commissionPaymentMethod =  CommissionPaymentMethod::findOrFail($id);
        return response()->json([
            'data' => $commissionPaymentMethod
        ]);
    }

    public function delete($id)
    {
        $commissionPaymentMethod =  CommissionPaymentMethod::findOrFail($id);
        $commissionPaymentMethod->delete();
        return response()->json([
            'message' => 'sucsess'
        ]);
    }
}
