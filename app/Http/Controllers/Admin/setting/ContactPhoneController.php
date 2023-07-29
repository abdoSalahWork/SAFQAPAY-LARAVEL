<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\ContactPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactPhoneController extends Controller
{
    public function index()
    {
        $contact_phones = ContactPhone::get();
        return response()->json(['data' => $contact_phones]);
    }
    public function show($id)
    {
        $contact_phones = ContactPhone::findOrFail($id);
        if ($contact_phones)
            return response()->json(['data' => $contact_phones]);
        else
            return response()->json(['data' => 'not found']);
    }
    public function store(Request $request)
    {
        $data = Validator::make($request->all(), [
            'number' => 'required|string|min:9|max:15',
            'type' => 'string|required'
        ]);
        if ($data->fails())
            return response()->json($data->errors(), 404);
        ContactPhone::create([
            'number' => $request->number,
            'type' => $request->type,
        ]);
        return response()->json(['message' => 'success']);
    }
    public function update(Request $request, $id)
    {
        $data = Validator::make($request->all(), [
            'number' => 'required|string',
            'type' => 'string|required'
        ]);
        if ($data->fails())
            return response()->json($data->errors(), 404);
        $contact_phones  = ContactPhone::find($id);
        if ($contact_phones) {
            $contact_phones->update([
                'number' => $request->number,
                'type' => $request->type,
            ]);
            return response()->json(['message' => 'success']);
        }
        else{
            return response()->json(['message' => 'not found'],404);

        }
    }
    public function delete($id)
    {
        $contact_phones  = ContactPhone::find($id);
        if ($contact_phones) {
            $contact_phones->delete();
            return response()->json(['message' => 'success']);
        } else
            return response()->json(['message' => 'not found'],404);
    }
}
