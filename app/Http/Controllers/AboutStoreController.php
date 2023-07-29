<?php

namespace App\Http\Controllers;

use App\Models\AboutStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AboutStoreController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $aboutStore = AboutStore::where('profile_id', $user->profile_business_id)->first();

        if ($aboutStore) {

            $urlImage = url("image/aboutStore/");

            return response()->json([
                'data' => $aboutStore,
                'urlImage' => $urlImage
            ]);
        }
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        $validateData = [
            'profile_id' => 'required|integer|exists:profile_businesses,id',
            'title' => 'required|string|max:255',
            'description' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif,bmp,tiff,webp,svg,heic,raw,ico',
        ];
        $requestData = [
            'profile_id' => $user->profile_business_id,
            'title' => $request->title,
            'description' => $request->description,
            'logo' => $request->logo,
        ];

        $data = Validator::make($requestData, $validateData);
        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        };

        $aboutStore = AboutStore::where('profile_id', $user->profile_business_id)->first();
        if ($aboutStore) {
            $pathldImage = "/public/images/aboutStore/$aboutStore->logo";
            $requestData['logo'] = request()->logo ?
                getdate()['year'] . getdate()['yday'] . time() . '.' . request()->logo->extension()
                : $aboutStore->logo;

            $aboutStore->update($requestData);

            if ($request->hasFile('logo')) {

                if (Storage::exists($pathldImage)) {
                    Storage::delete($pathldImage);
                }
                $request->file('logo')->storeAs("public/images/aboutStore/", $requestData['logo']);
            }
        } else {

            $requestData['logo'] = request()->logo ?
                getdate()['year'] . getdate()['yday'] . time() . '.' . request()->logo->extension()
                : null;
            AboutStore::create($requestData);
            if ($request->hasFile('logo')) {
                $request->file('logo')->storeAs("public/images/aboutStore/", $requestData['logo']);
            }
        }
        return response()->json([
            'message' => 'sucsess'
        ]);

        AboutStore::updateOrCreate();
    }


    //////////////////// Admin /////////////////
    public function index_admin()
    {

        $aboutStore = AboutStore::get();

        // return $aboutStore;
        if ($aboutStore) {

            $urlImage = url("image/aboutStore/");

            return response()->json([
                'data' => $aboutStore,
                'urlImage' => $urlImage
            ]);
        }
    }
    public function update_admin(Request $request, $aboutStoreId)
    {

        $aboutStore = AboutStore::find($aboutStoreId);
        if ($aboutStore) {
            $validateData = [
                'profile_id' => 'required|integer|exists:profile_businesses,id',
                'title' => 'required|string|max:255',
                'description' => 'required',
                'is_active' => 'nullable|boolean'
            ];
            $requestData = [
                'profile_id' => $aboutStore->profile_id,
                'title' => $aboutStore->title,
                'description' => $aboutStore->description,
                'logo' => $aboutStore->logo,
                'is_active' => $request->is_active ? $request->is_active : false,
            ];

            $data = Validator::make($requestData, $validateData);
            if ($data->fails()) {
                return response()->json($data->errors(), 404);
            };

            $aboutStore->update($requestData);
            return response()->json([
                'message' => 'Success',
            ]);
        }
        return response()->json([
            'message' => 'You can not update on this store',
        ], 404);
    }
}
