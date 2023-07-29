<?php

namespace App\Http\Controllers;

use App\Models\setting\SupportType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupportTypeController extends Controller
{
    public function index()
    {
        $support_types = SupportType::get();
        return response()->json(['data' => $support_types]);
    }
    public function store(Request $request)
    {
        $data = Validator::make($request->all(), ['name' => 'required']);
        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }
        SupportType::create(['name' => $request->name]);
        return response()->json(['message' => 'sucsess']);
    }
    public function show($id)
    {
        $support_type = SupportType::find($id);
        
       return $support_type ? response()->json(['data' => $support_type]) : response()->json(['message' => 'support_type is not found'], 404);
    }
    public function update(Request $request, $id)
    {
        $support_type = SupportType::find($id);
        if ($support_type) {
            $data = Validator::make($request->all(), ['name' => 'required']);
            if ($data->fails()) {
                return response()->json($data->errors(), 404);
            }
            $support_type->update(['name' => $request->name]);
            return response()->json(['message' => 'success']);
        }
        return response()->json(['message' => 'not found'],404);
    }

    public function delete($id)
    {
        $support_type = SupportType::find($id);
        if($support_type){
            $support_type->delete();
           return response()->json(['message' => 'sucsess']);
        }
       return  response()->json(['message' => 'support_type is not found'], 404);
        
    }

}
