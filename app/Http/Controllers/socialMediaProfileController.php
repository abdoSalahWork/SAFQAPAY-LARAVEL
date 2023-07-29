<?php

namespace App\Http\Controllers;

use App\Models\additional\socialMediaProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class socialMediaProfileController extends Controller
{
    function index(Request $request)
    {
        $profile = check_user($request->header('profile'));
        if ($profile) {

            $socialMediaProfile = socialMediaProfile::where('profile_business_id', $profile->id)
                ->with('socialMedia')->get();
            return response()->json(['data' => $socialMediaProfile]);
        }
        return response()->json(['message' => 'Please Choose Profile'], 404);
    }

    function store(Request $request)
    {


        $user = auth()->user();

        $validateData = [
            'url' => 'required|url|max:255',
            'social_id' => 'required|integer|exists:social_media,id',
            'profile_business_id' => 'required|integer|exists:profile_businesses,id',
        ];

        $requestData = [
            'url' => $request->url,
            'social_id' =>  $request->social_id,
            'profile_business_id' => $user->profile_business_id
        ];

        $data = Validator::make($requestData, $validateData);


        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        socialMediaProfile::create($requestData);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }
    function delete($id)
    {

        $user = auth()->user();
        $socialMediaProfileDelete = socialMediaProfile::find($id);

        if ($user->profile_business_id == $socialMediaProfileDelete->profile_business_id) {

            $socialMediaProfileDelete->delete();

            return response()->json([
                'message' => 'sucsess'
            ]);
        }

        return response()->json([
            'message' => 'You do not have permission to access this Socail Media'
        ], 404);
    }
}
