<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{

    public function index()
    {

        $contacts = Contact::first();
        return response()->json(['data' => $contacts]);
    }

    public function update(Request $request)
    {
        $rules = [
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'block' => 'nullable|string|max:255',
            'avenue' => 'nullable|string|max:255',
            'street' => 'required|string|max:255',

            'sales_support_officer_info' => 'required|string|max:255',
            'support_email' => 'required|string|max:50',
        ];
        $req = [
            'country' => $request->country,
            'city' => $request->city,
            'area' => $request->area,
            'block' => $request->block,
            'avenue' => $request->avenue,
            'street' => $request->street,
            'sales_support_officer_info' => $request->sales_support_officer_info,
            'support_email' => $request->support_email,
        ];
        $data = Validator::make($req, $rules);
        if ($data->fails())
            return  response()->json($data->errors(), 404);
        Contact::find(1)->update($req);
        return response()->json(['message' => 'success']);

    }
}
