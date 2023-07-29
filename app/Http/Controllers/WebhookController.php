<?php

namespace App\Http\Controllers;

use App\Models\ProfileBusiness;
use App\Models\WebHook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    //


    function webhookSecretKey()
    {
        $user = auth()->user();
        return response()->json(
            [
                'secretKey' => Str::random(64),
                'profile_id' => $user->profile_business_id
            ]
        );
    }

    public function Webhook(Request $request)
    {
        $user = auth()->user();

        $validateData = [
            'Endpoint' => 'required|string|active_url',
            'enable_secret_key' => 'nullable|boolean',
            'webhook_secret_key' => 'required_if:enable_secret_key,==,1',
            'transaction_status_changed' => 'nullable|boolean',
            'balance_transferred' => 'nullable|boolean',
            'recurring_status_changed' => 'nullable|boolean',
            'refund_status_changed' => 'nullable|boolean',
            'supplier_status_changed' => 'nullable|boolean',
        ];
        $requestData = [
            'profile_id' => $user->profile_business_id,
            'Endpoint' => $request->endpoint,
            'enable_secret_key' => $request->enable_secret_key ? true : false,
            'webhook_secret_key' => $request->webhook_secret_key? $request->webhook_secret_key : false,
            'transaction_status_changed' => $request->transaction_status_changed ? true : false,
            'balance_transferred' => $request->balance_transferred ? true : false,
            'recurring_status_changed' => $request->recurring_status_changed ? true : false,
            'refund_status_changed' => $request->refund_status_changed ? true : false,
            'supplier_status_changed' => $request->supplier_status_changed ? true : false,
        ];

        $data = Validator::make($requestData, $validateData);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        };
        ProfileBusiness::find($user->profile_business_id)->update([
            'enable_webhook' => $request->enable_webhook ? $request->enable_webhook : false
        ]);

        WebHook::updateOrCreate(
            [
                'profile_id' => $user->profile_business_id
            ],
            $requestData
        );


        return response()->json(['message' => 'success']);
    }
}
