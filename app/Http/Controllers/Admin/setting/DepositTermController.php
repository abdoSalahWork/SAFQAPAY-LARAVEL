<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\DepositTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepositTermController extends Controller
{
    function index()
    {
        $depositTerms = DepositTerm::get();
        return response()->json([
            'data' => $depositTerms
        ]);
    }
    public function show($id)
    {
        $depositTerm = DepositTerm::find($id);
        return $depositTerm ? response()->json(['data' => $depositTerm]) : response()->json(['message' => 'depositTerm is not found'],404);
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

        DepositTerm::create($requestData);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }

    function update(Request $request, $id)
    {
        $depositTermUpdate = DepositTerm::findOrFail($id);

        if ($depositTermUpdate) {
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

            $depositTermUpdate->update($requestData);

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'This Deposit Term Is not found'
        ] , 404);
    }
    function delete($id)
    {
        $depositTermDelete = DepositTerm::findOrFail($id);

        if ($depositTermDelete) {

            $depositTermDelete->delete();

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'This Deposit Term Is not found'

        ] , 404);
    }
}
