<?php

namespace App\Http\Controllers;

use App\Models\ProfileBusiness;
use App\Models\User;
use App\Services\UpdateFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileBusinessController extends Controller
{
    private $updateFileService;
    function __construct(UpdateFileService $updateFile)
    {
        $this->updateFileService = $updateFile;
    }
    function index(Request $request)
    {
        $profile = check_user($request->header('profile'));
        if ($profile) {
            $profileBusiness = ProfileBusiness::with(['phoneNumberCode', 'bank', 'country'])->find($profile->id);
            $profileBusiness->country->flag = url('image/country') . '/' . $profileBusiness->country->flag;
            if ($profileBusiness['logo']) {
                $profileBusiness['logo'] = route('image.profile', [$profile->id, $profileBusiness['logo']]);
            }
            return response()->json([
                'data' => $profileBusiness
            ]);
        }
        return response()->json(['message' => 'Please Choose Profile'], 404);
    }

    function update(Request $request)
    {
        $user = auth()->user();
        $profileBusiness = ProfileBusiness::find($user->profile_business_id);
        $validateData = [
            'company_name' => 'required|string|max:255|unique:profile_businesses,company_name,' . $profileBusiness->id,
            'category_id' =>  'required|integer|exists:categories,id',
            'website_url' => 'nullable|url',
            'language_id' =>  'nullable|integer|exists:languages,id',
            'phone_number_code_id' => 'required|integer|exists:countries,id',
            'phone_number' =>  'required|max:20',
            'invoice_expiry_after_type_id' => 'required|integer|exists:invoice_expiry_after_types,id',
            'invoice_expiry_after_number' => 'required|integer',
            'custom_sms_ar' => 'nullable|string',
            'custom_sms_en' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg'
        ];
        $requestData = [
            'company_name' =>  $request->company_name,
            'category_id' =>   $request->category_id,
            'website_url' => $request->website_url,
            'language_id' =>  $request->language_id,
            'phone_number_code_id' => $request->phone_number_code_id,
            'phone_number' =>  $request->phone_number,
            'invoice_expiry_after_type_id' => $request->invoice_expiry_after_type_id,
            'invoice_expiry_after_number' => $request->invoice_expiry_after_number,
            'custom_sms_ar' => $request->custom_sms_ar,
            'custom_sms_en' => $request->custom_sms_en,
            'terms_and_conditions' => $request->terms_and_conditions,
            'logo' => $request->logo
        ];

        $data = Validator::make($requestData, $validateData);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        $pathldImage = "/public/images/profile/$user->profile_business_id/$profileBusiness->logo";
        $requestData['logo'] = $this->updateFileService->updateFile($pathldImage, $profileBusiness->logo, 'logo');

        $profileBusiness->update($requestData);

        if ($request->hasFile('logo')) {
            $request->file('logo')->storeAs("public/images/profile/" . $user->profile_business_id, $requestData['logo']);
        }
        return response()->json([
            'message' => 'sucsess'
        ]);
    }
}
