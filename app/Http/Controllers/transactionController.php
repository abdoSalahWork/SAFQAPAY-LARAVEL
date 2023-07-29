<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class transactionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transactions = Transaction::where('transaction_status', true)->where('profile_id', $user->profile_business_id)->get();
        return response()->json(['data' => $transactions]);
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        $manthNow = Carbon::parse()->format('m/y');
        $rules_transaction = [
            'profile_id' => 'required|exists:profile_businesses,id',
            'invoice_id' => 'required|exists:invoices,id',
            'payment_gateway' => 'required|exists:payment_methods,id',
            'card_holder_name' => 'required|string',
            'card_number' => 'required|string|max:16',
            'expiration_date' => "required|date_format:m/y|after_or_equal:$manthNow",
            'security_code' => 'required|string|max:3',
            'payment_id' => 'required|integer',
            'authorization_id' => 'required|integer',
            'track_iD' => 'required|integer',
            'reference_id' => 'required|integer',
            'errror' => 'nullable|string',
        ];
        $request_transaction = [
            'invoice_id' => $request->invoice_id,
            'profile_id' => $user->profile_business_id,
            'transaction_status' => true,
            'payment_gateway' => $request->payment_gateway,
            'card_holder_name' => $request->card_holder_name,
            'card_number' => $request->card_number,
            'expiration_date' => $request->expiration_date,
            'security_code' => $request->security_code,
            'payment_id' => $request->payment_id,
            'authorization_id' => $request->authorization_id,
            'track_iD' => $request->track_iD,
            'reference_id' => $request->reference_id,
            'errror' => $request->invoice_id,
        ];

        $data = Validator::make($request_transaction, $rules_transaction);
        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }
        // bank process and bank response

        if ($request->bank_message == 'success') {
            Transaction::create($request_transaction);
            return response()->json(['message' => 'success']);
        }
        return response()->json(['message' => 'success']);
    }
}
