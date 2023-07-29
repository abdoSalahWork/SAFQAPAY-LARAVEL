<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\RecurringInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecurringIntervalController extends Controller
{
    function index()
    {
        $recurringIntervals = RecurringInterval::get();
        return response()->json([
            'data' => $recurringIntervals
        ]);
    }
    public function show($id)
    {
        $recurringInterval = RecurringInterval::find($id);
        return $recurringInterval ? response()->json(['data' => $recurringInterval])
            : response()->json(['message' => 'recurringInterval is not found', 404]);
    }
    function store(Request $request)
    {
        $validateData = [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
        ];
        $requestData = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
        ];

        $data = Validator::make($requestData, $validateData);
        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        RecurringInterval::create($requestData);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }

    function update(Request $request, $id)
    {
        $recurringIntervalUpdate = RecurringInterval::find($id);

        if ($recurringIntervalUpdate) {
            $requestData = [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,

            ];
            $validateData = [
                'name_en' => 'required|string|max:255', // unique yes or No
                'name_ar' => 'required|string|max:255',

            ];
            $data = Validator::make($requestData, $validateData);
            if ($data->fails()) {
                return response()->json($data->errors(), 404);
            }

            $recurringIntervalUpdate->update($requestData);

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'This Recurring Interval Is not found'
        ],404);
    }
    function delete($id)
    {
        $recurringIntervalDelete = RecurringInterval::find($id);

        if ($recurringIntervalDelete) {

            $recurringIntervalDelete->delete();

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'This Recurring Interval Is not found'
        ],404);
    }
}
