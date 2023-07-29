<?php

namespace App\Http\Controllers;

use App\Models\Firebase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FirebaseController extends Controller
{
    public function addToken(Request $request)
    {
        $user = auth()->user();

        $validateData = Validator::make($data = $request->all(), [
            'token_user' => 'required|string',
        ]);

        if ($validateData->fails()) {
            return response()->json($validateData->errors(), 404);
        }
        Firebase::create([
            'user_id' => $user->id,
            'token_user' => $request->token_user
        ]);
        return response()->json([
            'message' => 'success'
        ]);
    }
}
