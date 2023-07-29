<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethodUser;
use App\Models\setting\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentMethodUserController extends Controller
{
    public function index(Request $request)
    {
        $profile = check_user($request->header('profile'));

        if ($profile) {
            $paymentsMethod = PaymentMethod::with('commissionPaymentMethod')->with('payment_method')->get();

            // $payments = PaymentMethodUser::where('profile_id', $profile->id)->get();
            // if (count($payments) != $countPaymentInAdmin) {
            //     $this->create();
            // }

            return response()->json([
                'data' => $paymentsMethod
            ]);
        }
        return response()->json(['message' => 'Please Choose Profile'], 404);
    }
    public function update(Request $request)
    {
        $user = auth()->user();

        for ($i = 0; $i < count($request->commission_from_id); $i++) {
            $rules = [
                "commission_from_id" => 'required|integer|exists:commission_from,id',
                "payment_method_id" => 'required|integer|exists:payment_methods,id',
                "is_active" => 'required|boolean'
            ];
            $req = [
                "commission_from_id" => $request['commission_from_id'][$i],
                "payment_method_id" => $request['payment_method_id'][$i],
                "is_active" => $request['is_active'][$i],
            ];
            // if ($request->is_active != 1 && $request->is_active != 'true')
            //     $req['is_active'] = false;
            // else
            //     $req['is_active'] = true;
            $data = Validator::make($req, $rules);

            if ($data->fails()) return response()->json($data->errors(), 404);

            $payment = PaymentMethodUser::where('profile_id', $user->profile_business_id)
                ->where('payment_method_id', $req['payment_method_id'])->firstorFail();
            $payment->update([
                'commission_from_id' => $req['commission_from_id'],
                'is_active' => $req['is_active'],
            ]);
        }
        return response()->json(['message' => 'sucsess']);
    }

    public function create()
    {
        PaymentMethodUser::upsert();
    }
}
