<?php
namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    function index()
    {
        $cities = City::with('country')->get();
        return response()->json(['data' => $cities]);
    }
    public function show($id)
    {
        $city = City::with('country')->find($id);
        return $city ?  response()->json(['data' => $city]) : response()->json(['message' => 'city is not found'],404);
    }

    function cityProfile()
    {
        $user = auth()->user();
        $userProfile = User::with('profileBusiness')->find($user->id);

        $cities = City::where('country_id',$userProfile->profileBusiness->country_id)
        ->with('country')->get();
        return response()->json(['data' => $cities]);
    }


    function store(Request $request)
    {
        $validateData = [
            'name_en' => "required|string|max:255|unique:cities,name_en,NULL,id,country_id,$request->country_id",
            'name_ar' => "required|string|max:255|unique:cities,name_ar,NULL,id,country_id,$request->country_id",
            'country_id' => 'required|integer|exists:countries,id',
        ];
        $requestData = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'country_id' => $request->country_id,
        ];

        $data = Validator::make($requestData, $validateData);
        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        City::create($requestData);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }

    function update(Request $request,$id)
    {

        $cityUpdate = City::findOrFail($id);

        if ($cityUpdate) {
            $validateData = [
                'name_en' => "required|string|max:255|unique:cities,name_en,$id,id,country_id,$request->country_id",
                'name_ar' => "required|string|max:255|unique:cities,name_ar,$id,id,country_id,$request->country_id",
                'country_id' => 'required|integer|exists:countries,id',
            ];
            $requestData = [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'country_id' => $request->country_id,
            ];

            $data = Validator::make($requestData, $validateData);
            if ($data->fails()) {
                return response()->json($data->errors(), 404);
            }

            $cityUpdate->update($requestData);

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json(['message' => "this City is not found"],404);
    }
    function delete($id)
    {
        $cityDelete = City::findOrFail($id);

        if ($cityDelete) {

            $cityDelete->delete();

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => "this City is not found"
        ],404);
    }
}
