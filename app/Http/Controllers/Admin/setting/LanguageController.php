<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    function index()
    {
        $languages = Language::get();
        return response()->json([
            'data' => $languages
        ]);
    }
    public function show($id)
    {
        $language = Language::findOrFail($id);
        return  response()->json(['data' => $language]) ;
    }
    function store(Request $request)
    {
        $validateData = [
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'default' => 'boolean|nullable',
        ];
        $requestData = [
            'name' => $request->name,
            'short_name' => $request->short_name,
            'slug' => $request->slug,
            'default' => $request->default ? true : false,
        ];

        $data = Validator::make($requestData, $validateData);
        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        Language::create($requestData);

        return response()->json([
            'message' => 'sucsess'
        ]);
    }

    function update(Request $request, $id)
    {
        $languageUpdate = Language::findOrFail($id);

        if ($languageUpdate) {
            $requestData = [
                'name' => $request->name,
                'short_name' => $request->short_name,
                'slug' => $request->slug,
                'default' => $request->default ? true : false,
            ];

            $validateData = [
                'name' => 'required|string|max:255',
                'short_name' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'default' => 'boolean|nullable',

            ];

            $data = Validator::make($requestData, $validateData);

            if ($data->fails()) {
                return response()->json($data->errors(), 404);
            }

            $languageUpdate->update($requestData);

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'The Language Is not found'
        ] , 404);
    }
    function delete($id)
    {
        $languageDelete = Language::findOrFail($id);

        if ($languageDelete) {

            $languageDelete->delete();

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'The Language Is not found'
        ] ,404);
    }
}
