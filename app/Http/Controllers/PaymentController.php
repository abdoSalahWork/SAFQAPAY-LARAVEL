<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $profile = check_user($request->header('profile'));
        if ($profile) {

            $payments = Payment::where('profile_business_id', $profile->id)->with('currency')->with('language')->get();

            return response()->json(['data' => $payments]);
        }
        return response()->json(['message' => 'Please Choose Profile'], 404);
    }

    public function showPaymentDetails($payment_id)
    {
        $payment = Payment::select('id', 'profile_business_id', 'created_at', 'payment_title', 'payment_amount' ,'currency_id')
            ->with(['profile' => function ($q) {
                $q->selected();
                $q->with(['aboutStore' => function ($q) {
                    $q->where('is_active', true);
                }]);
            }])
            ->with('currency')
            ->findOrFail($payment_id);
        $urlLogoStore = url("image/aboutStore/");

        return response()->json(['data' => $payment, 'urlLogoStore' => $urlLogoStore]);;
    }

    public function store(Request $request)
    {

        $user = auth()->user();

        $validateData = Validator::make($request->all(), [
            'payment_title' => 'required|string|max:20',
            'payment_amount' => 'required_if:open_amount,==,0|integer',

            'currency_id' => 'required|integer|exists:countries,id',
            'language_id' => 'required|integer|exists:languages,id',
            'open_amount' => 'boolean|required',
            'comment' => 'string|nullable',
            'terms_and_conditions' => 'string|nullable',

            'min_amount' => 'required_if:open_amount,==,1|integer|lt:max_amount',
            'max_amount' => 'required_if:open_amount,==,1|integer|gt:min_amount',

        ]);
        if ($validateData->fails()) {
            return response()->json($validateData->errors(), 404);
        }
        if ($request->open_amount) {
            $request['payment_amount'] = $request->max_amount;
        }
        Payment::create([
            'manager_user_id' => $user->id,
            'profile_business_id' => $user->profile_business_id,
            'payment_title' => $request->payment_title,
            'payment_amount' => $request['payment_amount'],
            'currency_id' =>  $request->currency_id,
            'language_id' =>  $request->language_id,
            'open_amount' =>  $request->open_amount,
            'comment' => $request->comment,
            'terms_and_conditions' => $request->terms_and_conditions,
            'min_amount' =>  $request->min_amount,
            'max_amount' =>  $request->max_amount
        ]);
        return response()->json([
            "message" => "Success"
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();
        $payment = Payment::with('transaction')->with('view')->with('currency')->find($id);
        // $payment = Payment::with('language')->with('invoice')->find($id);
        if ($payment) {
            if ($user->profile_business_id == $payment->profile_business_id) {
                return response()->json(['data' => $payment]);
            }
            return response()->json([
                'message' => 'You do not have permission to access this Payment'
            ], 404);
        }
        return response()->json([
            'message' => 'this Payment is not found or deleted'
        ], 404);
    }

    public function update(Request $request, $id)
    {

        $user = auth()->user();
        $payment = Payment::findOrFail($id);
        if ($user->profile_business_id == $payment->profile_business_id) {
            $validateData = Validator::make($request->all(), [
                'payment_title' => 'required|string|max:20',
                'payment_amount' => 'required|integer',
                'currency_id' => 'required|integer|exists:countries,id',
                'language_id' => 'required|integer|exists:languages,id',
                'open_amount' => 'boolean|required',
                'comment' => 'string|nullable',
                'min_amount' => 'integer|nullable|lt:max_amount',
                'max_amount' => 'integer|nullable|gt:min_amount',
                'terms_and_conditions' => 'string|nullable',
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 404);
            }
            $payment->update([
                'manager_user_id' => $user->id,
                'profile_business_id' => $user->profile_business_id,
                'payment_title' => $request->payment_title,
                'payment_amount' => $request->payment_amount,
                'currency_id' =>  $request->currency_id,
                'language_id' =>  $request->language_id,
                'open_amount' =>  $request->open_amount,
                'comment' => $request->comment,
                'min_amount' =>  $request->min_amount,
                'max_amount' =>  $request->max_amount,
                'terms_and_conditions' => $request->terms_and_conditions,
            ]);
            return response()->json([
                "message" => "Success"
            ]);
        }
        return response()->json([
            'message' => 'You do not have permission to access this Payment'
        ], 404);
    }

    public function delete($id)
    {


        $user = auth()->user();
        $payment = Payment::find($id);
        if ($payment) {
            if ($payment->profile_business_id == $user->profile_business_id) {
                $payment->delete();
                return response()->json([
                    "message" => "Success"
                ]);
            }
        }
        return response()->json([
            'message' => 'You do not have permission to delete this payment'
        ]);
    }
}
