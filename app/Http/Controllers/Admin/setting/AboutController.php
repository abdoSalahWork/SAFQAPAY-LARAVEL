<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AboutController extends Controller
{
    public function index()
    {
        $about = About::get();
        return response()->json(['data' => $about]);
    }
    

    public function show($id)
    {
        $about = About::findOrFail($id);
        return response()->json(['data' => $about]);
    }


    public function store(Request $request)
    {
        $data = Validator::make($request->all(), ['about' => 'required|string']);
        if ($data->fails()) return response()->json($data->errors(), 404);
        About::create(['about' => $request->about]);
        return response()->json(['message' => 'success']);
    }


    public function update(Request $request, $id)
    {
        $data = Validator::make($request->all(), ['about' => 'required|string']);
        if ($data->fails())
            return response()->json($data->errors(), 404);
        About::find($id)->update(['about' => $request->about]);
        return response()->json(['message' => 'success']);
    }


    public function delete($id)
    {
        About::find($id)->delete();
        return response()->json(['message' => 'success']);
    }
}
