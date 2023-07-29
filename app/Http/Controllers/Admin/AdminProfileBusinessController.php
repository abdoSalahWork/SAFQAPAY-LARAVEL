<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileBusiness;
use App\Services\NotificationService;
use App\Services\UpdateFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class AdminProfileBusinessController extends Controller
{
    private $updateFileService;
    private $notificationServiceClass;

    function __construct(
        UpdateFileService $updateFile,
        NotificationService $notificationService,
    ) {
        $this->updateFileService = $updateFile;
        $this->notificationServiceClass = $notificationService;
    }
    public function index()
    {

        $profileBusiness = ProfileBusiness::get();

        return response()->json([
            'data' => $profileBusiness
        ]);
    }

    public function show($id)
    {
        $profileBusiness = ProfileBusiness::findOrFail($id);
        if ($profileBusiness->logo) {
            $profileBusiness->logo = route('image.profile', [$profileBusiness->id, $profileBusiness['logo']]);
        }
        return response()->json([
            'data' => $profileBusiness
        ]);
    }

    public function update(Request $request, $id)
    {
        $profileBusiness = ProfileBusiness::findOrFail($id);
        $validator = [
            'country_id' => "required|integer|exists:countries,id,country_active,1",
            'phone_number_code_id' => 'required|integer|exists:countries,id',
            'phone_number' =>  'required|max:20',
            'business_type_id' =>  'required|integer|max:20',
            'category_id' =>  'required|integer',
            'language_id' =>  'nullable|integer',

            'company_name' => 'required|string|max:255|unique:profile_businesses,company_name,' . $profileBusiness->id,
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'work_email' => 'required|email|max:255',

            'bank_account_name' => 'required|string|max:255',
            'bank_id' => "required|integer|exists:banks,id,country_id,$request->country_id",
            'account_number' => 'required|string|max:255',
            'iban' => 'required|string|max:255',
            'invoice_expiry_after_number' => 'nullable'



        ];
        $requestData = [
            'country_id' => $request->country_id,
            'phone_number_code_id' => $request->phone_number_code_id,
            'phone_number' => $request->phone_number,
            'business_type_id' => $request->business_type_id,
            'category_id' => $request->category_id,
            'language_id' => $request->language_id,
            'company_name' => $request->company_name,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'work_email' => $request->work_email,
            'bank_account_name' => $request->bank_account_name,
            'bank_id' => $request->bank_id,
            'account_number' => $request->account_number,
            'iban' => $request->iban,


            'logo' => $request->logo,
            'website_url' => $request->website_url,
            'custom_sms_ar' => $request->custom_sms_ar,
            'custom_sms_en' => $request->custom_sms_en,
            'terms_and_conditions' => $request->terms_and_conditions,
            'products_delivery_fees' => $request->products_delivery_fees ? $request->products_delivery_fees : 0,
            'promo_code' => $request->promo_code,
            'invoice_expiry_after_number' => $request->invoice_expiry_after_number ? $request->invoice_expiry_after_number : 1,
            'bank_account_letter' => $request->bank_account_letter,
            'others' => $request->others,
            'civil_id' => $request->civil_id,
            'civil_id_back' => $request->civil_id_back,
            'enable_new_design' => $request->enable_new_design ? true : false,
            'show_all_currencies' => $request->show_all_currencies ? true : false,
            'enable_card_view' => $request->enable_card_view ? true : false,
            'theme_color' => $request->theme_color,
            'is_approval' => $request->is_approval ? $request->is_approval : 0,
            'is_enable' => $request->is_enable ? $request->is_enable : 0,
        ];

        $data = Validator::make($requestData, $validator);
        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }
        if ($profileBusiness->is_approval != $requestData['is_approval']) {
            $domain = URL::to('/');
            $api = "$domain/api/profile_business";
            $text = "Your Profile  $profileBusiness->company_name  change approve status";
            $column = 'notification_approve_vendor_account';

            $this->notificationServiceClass->notification($profileBusiness->id, $profileBusiness->id, $text, $api, $column ,$profileBusiness->company_name);
        }

        $pathldImage = "/public/images/profile/$profileBusiness->id/$profileBusiness->logo";
        $requestData['logo'] = $this->updateFileService->updateFile($pathldImage, $profileBusiness->logo, 'logo');

        $profileBusiness->update($requestData);

        if ($request->hasFile('logo')) {
            $request->file('logo')->storeAs("public/images/profile/" . $profileBusiness->id, $requestData['logo']);
        }

        return response()->json(['message' => 'success']);
    }
}
