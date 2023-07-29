<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\InvoiceExpiryAfterType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceExpiryAfterTypeController extends Controller
{
    function index()
    {
        $invoiceExpiry = InvoiceExpiryAfterType::where('is_active', true)->get();
        return response()->json([
            'data' => $invoiceExpiry
        ]);
    }

    function indexAdmin()
    {
        $invoiceExpiry = InvoiceExpiryAfterType::get();
        return response()->json([
            'data' => $invoiceExpiry
        ]);
    }

    public function show($id)
    {
        $invoiceExpiryAfterType = InvoiceExpiryAfterType::find($id);
        return $invoiceExpiryAfterType ? response()->json(['data' => $invoiceExpiryAfterType]) : response()->json(['message' => 'invoiceExpiryAfterType is not found', 404]);
    }


    function update(Request $request, $id)
    {
        $invoiceExpiryUpdate = InvoiceExpiryAfterType::find($id);

        if ($invoiceExpiryUpdate) {
            $requestData = [
                'is_active' => $request->is_active ? $request->is_active : false,
            ];

            $validateData = [
                'is_active' => 'nullable|boolean',
            ];

            $data = Validator::make($requestData, $validateData);

            if ($data->fails()) {
                return response()->json($data->errors(), 404);
            }

            $invoiceExpiryUpdate->update($requestData);

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'The Invoice Expiry Is not found'
        ], 404);
    }
}
