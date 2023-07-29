<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    function index()
    {
        $areas = Area::with('city')->get();
        return response()->json(['data' => $areas]);
    }
    public function show($id)
    {
        $area = Area::with('city')->findOrFail($id);
        return $area ? response()->json(['data' => $area]) : response()->json(['message' => 'area is not found',404]);
    }
    function store(Request $request)
    {
        $validateData = [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'city_id' => 'required|integer|exists:cities,id',
        ];
        $requestData = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'city_id' => $request->city_id,
        ];

        $data = Validator::make($requestData, $validateData);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        Area::create($requestData);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }


    function update(Request $request, $id)
    {

        $areaUpdate = Area::find($id);

        if ($areaUpdate) {
            $validateData = [
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'city_id' => 'required|integer|exists:cities,id',
            ];
            $requestData = [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'city_id' => $request->city_id,
            ];

            $data = Validator::make($requestData, $validateData);

            if ($data->fails()) {
                return response()->json($data->errors(), 404);
            }

            $areaUpdate->update($requestData);

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json(['message' => "this Area is not found"],404);
    }
    function delete($id)
    {
        $areaDelete = Area::find($id);

        $check =  $areaDelete ? $areaDelete->delete() : null;

        return $check ? response()->json([ 'message' => 'sucsess' ])
        : response()->json(['message' => "this Area is not found"], 404);
    }
}
