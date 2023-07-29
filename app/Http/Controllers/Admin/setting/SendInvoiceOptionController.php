<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\Invoice\SendInvoiceOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SendInvoiceOptionController extends Controller
{
    function index()
    {
        $sendInvoiceOptions = SendInvoiceOption::get();
        return response()->json([
            'data' => $sendInvoiceOptions
        ]);
    }
    public function show($id)
    {
        $sendInvoiceOption = SendInvoiceOption::find($id);
        return $sendInvoiceOption ? response()->json(['data' => $sendInvoiceOption]) : response()->json(['message' => 'sendInvoiceOption is not found', 404]);
    }
    function store(Request $request)
    {
        $validateData = [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'default' => 'boolean|nullable',
        ];
        $requestData = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'default' => $request->default ? true : false,
        ];

        $data = Validator::make($requestData, $validateData);
        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        SendInvoiceOption::create($requestData);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }

    function update(Request $request, $id)
    {
        $sendInvoiceOptionUpdate = SendInvoiceOption::findOrFail($id);

        if ($sendInvoiceOptionUpdate) {
            $requestData = [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'default' => $request->default ? true : false,

            ];
            $validateData = [
                'name_en' => 'required|string|max:255', // unique yes or No
                'name_ar' => 'required|string|max:255',
                'default' => 'nullable|boolean',

            ];
            $data = Validator::make($requestData, $validateData);
            if ($data->fails()) {
                return response()->json($data->errors(), 404);
            }

            $sendInvoiceOptionUpdate->update($requestData);

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'This Send Invoice Option Is not found'
        ],404);
    }
    function delete($id)
    {
        $sendInvoiceOptionDelete = SendInvoiceOption::findOrFail($id);

        if ($sendInvoiceOptionDelete) {

            $sendInvoiceOptionDelete->delete();

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'This Send Invoice Option Is not found'
        ],404);
    }
}
