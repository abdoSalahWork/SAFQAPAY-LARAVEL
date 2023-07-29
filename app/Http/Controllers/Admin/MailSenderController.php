<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\setting\MailSenderInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MailSenderController extends Controller
{
    function index()
    {
        $data = MailSenderInformation::first();
        return response()->json([
            'data' => $data
        ]);
    }
    public function update(Request $request)
    {
        $data = MailSenderInformation::first();

        $validateData = Validator::make($request->all(), [
            'transport' => 'required|string|max:10',
            'host' => 'required|string',
            'port' => 'required|integer',
            'encryption' =>  'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
            'address' => 'required|string|email',
            'name' => 'required|string',
        ]);
        if ($validateData->fails()) {
            return response()->json($validateData->errors(), 404);
        }
        $data->update($request->all());
        return response()->json([
            'message' => 'sucsess'
        ]);
    }
}
