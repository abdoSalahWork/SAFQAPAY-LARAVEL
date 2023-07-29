<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class SocialMediaController extends Controller
{

    function index()
    {
        $socialMedia = SocialMedia::get();
        $urlImage = url("image/socialMedia/");

        return response()->json([
            'data' => $socialMedia,
            'urlImage' => $urlImage
        ]);
    }
    function store(Request $request)
    {
        $validateData = [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'icon' => 'required|image|mimes:jpg,png,webp',

        ];
        $requestData = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'icon' => $request->icon
        ];

        $data = Validator::make($requestData, $validateData);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }
        $requestData['icon'] = request()->icon ?
            getdate()['year'] . getdate()['yday'] . time() . '.' . request()->icon->extension()
            : null;

        SocialMedia::create($requestData);

        if ($request->hasFile('icon')) {
            $request->file('icon')->storeAs("public/images/socialMedia/", $requestData['icon']);
        }


        return response()->json([
            'message' => 'sucsess'
        ]);
    }
    public function show($id)
    {
        $socialMedia = SocialMedia::find($id);
        $urlImage = url("image/socialMedia/$socialMedia->icon");

        return  $socialMedia ? response()->json(['data' => $socialMedia, 'urlImage' => $urlImage])
            : response()->json(['message' => 'socialMedia is not found'], 404);
    }
    function update(Request $request, $id)
    {
        $socialMediaUpdate = SocialMedia::findOrFail($id);

        if ($socialMediaUpdate) {
            $requestData = [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'icon' => $request->icon

            ];
            $validateData = [
                'name_en' => 'required|string|max:255', // unique yes or No
                'name_ar' => 'required|string|max:255',
                'icon' => 'nullable|image|mimes:jpg,png,webp',


            ];
            $data = Validator::make($requestData, $validateData);
            if ($data->fails()) {
                return response()->json($data->errors(), 404);
            }

            // $requestData['product_image'] = request()->product_image
            // ? getdate()['year'] . getdate()['yday'] . time() . '.' . request()->product_image->extension()
            // : $productUpdate->product_image;


            $pathldImage = "/public/images/socialMedia/$socialMediaUpdate->icon";
            $requestData['icon'] = request()->icon ?
                getdate()['year'] . getdate()['yday'] . time() . '.' . request()->icon->extension()
                : $socialMediaUpdate->icon;

            $socialMediaUpdate->update($requestData);


            if ($request->hasFile('icon')) {
                if (Storage::exists($pathldImage)) {
                    Storage::delete($pathldImage);
                }
                $request->file('icon')->storeAs("public/images/socialMedia/", $requestData['icon']);
            }

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'This Send Invoice Option Is not found'
        ], 404);
    }
    function delete($id)
    {
        $socialMediaDelete = SocialMedia::findOrFail($id);

        if ($socialMediaDelete) {

            $socialMediaDelete->delete();

            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'This Send Invoice Option Is not found'
        ], 404);
    }
}
