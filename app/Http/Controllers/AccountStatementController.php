<?php

namespace App\Http\Controllers;

use App\Models\AccountStatement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountStatementController extends Controller
{

    public function index(Request $request)
    {
        $profile = check_user($request->header('profile'));
        if ($profile) {
            $AccountStatement = AccountStatement::where('profile_id', $profile->id)->with(['profile' => function ($q) {
                $q->select('id', 'company_name' , 'work_email');
            }])->get();
            return response()->json(['data' => $AccountStatement]);
        }
        return response()->json(['message' => 'Please Choose Profile'], 404);
    }
    public function getDataManth(Request $request)
    {
        $user = auth()->user();
        $validateData = Validator::make($data = $request->all(), [
            'date' => 'required|date_format:Y-m'
        ]);
        if ($validateData->fails()) {
            return response()->json($validateData->errors(), 404);
        }
        $month = Carbon::parse($request->date)->format('m');
        $year = Carbon::parse($request->date)->format('Y');
        return response()->json(['data' => AccountStatement::where('profile_id', $user->profile_business_id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get()]);
    }

    public function index_admin()
    {
        $AccountStatement = AccountStatement::with(['profile' => function ($q) {
            $q->select('id', 'company_name' , 'work_email');
        }])->get();
        return response()->json(['data' => $AccountStatement]);
    }
}
