<?php

namespace App\Http\Controllers;

use App\Models\Addresse;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddresseController extends Controller
{
    function index(Request $request)
    {
        $request['token']= $request->header('token');
        $user = auth()->user();
        $addresses = Addresse::where('manager_user_id', $user->id)
            ->with('city')->with('addressType')
            ->with('area')->get();
        return response()->json([
            'data' => $addresses
        ]);
    }

    function show(Request $request,$id)
    {
        $request['token']= $request->header('token');
        $user = auth()->user();
        $addresseShow = Addresse::with('city')
        ->with('addressType')->with('area')->find($id);
        if ($user->id == $addresseShow->manager_user_id) {

            return response()->json([
                'data' => $addresseShow

            ]);
        }
        return response()->json([
            'message' => 'You have no control over this address'
        ], 404);
    }

    function store(Request $request)
    {
        $user = auth()->user();

        $validateData = [
            'addressType_id' => 'required|integer|exists:address_types,id',
            'city_id' => 'required|integer|exists:cities,id,country_id,' . $user->profileBusiness->country_id,
            'area_id' => 'required|integer|exists:areas,id,city_id,' . $request->city_id,
            'block' => 'required|string|max:255',
            'avenue' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'bldgNo' => 'nullable|string|max:255',
            'appartment' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'instructions' => 'nullable|string|max:255',
        ];
        $requestData = [
            'addressType_id' => $request->addressType_id,
            'city_id' => $request->city_id,
            'area_id' => $request->area_id,
            'block' => $request->block,
            'avenue' => $request->avenue,
            'street' => $request->street,
            'bldgNo' => $request->bldgNo,
            'appartment' => $request->appartment,
            'floor' => $request->floor,
            'instructions' => $request->instructions,
            'manager_user_id' => $user->id,
            'profile_business_id' => $user->profile_business_id
        ];
        $data = Validator::make($requestData, $validateData);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }
        Addresse::create($requestData);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }

    function update(Request $request, $id)
    {

        $user = auth()->user();
        $addresseUpdate = Addresse::findOrFail($id);
        if ($user->id == $addresseUpdate->manager_user_id) {
            $validateData = [
                'addressType_id' => 'required|integer|exists:address_types,id',
                'city_id' => 'required|integer|exists:cities,id,country_id,' . $user->profileBusiness->country_id,
                'area_id' => 'required|integer|exists:areas,id,city_id,' . $request->city_id,
                'block' => 'required|string|max:255',
                'avenue' => 'required|string|max:255',
                'street' => 'required|string|max:255',
                'bldgNo' => 'nullable|string|max:255',
                'appartment' => 'nullable|string|max:255',
                'floor' => 'nullable|string|max:255',
                'instructions' => 'nullable|string|max:255',
            ];
            $requestData = [
                'addressType_id' => $request->addressType_id,
                'city_id' => $request->city_id,
                'area_id' => $request->area_id,
                'block' => $request->block,
                'avenue' => $request->avenue,
                'street' => $request->street,
                'bldgNo' => $request->bldgNo,
                'appartment' => $request->appartment,
                'floor' => $request->floor,
                'instructions' => $request->instructions,
                'manager_user_id' => $user->id,
                'profile_business_id' => $user->profile_business_id
            ];
            $data = Validator::make($requestData, $validateData);
            $addresseUpdate->update($requestData);

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'You have no control over this address'
        ], 404);
    }
    function delete(Request $request,$id)
    {

        $user = auth()->user();
        $addresseDelete = Addresse::findOrFail($id);
        if ($user->id == $addresseDelete->manager_user_id) {

            $addresseDelete->delete();

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'You have no control over this address'
        ], 404);
    }
}
