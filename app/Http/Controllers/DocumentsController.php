<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentsController extends Controller
{

    private $notificationServiceClass;
    public function __construct( NotificationService $notificationService)
    {
        $this->notificationServiceClass = $notificationService;
    }


    public function index(Request $request)
    {
        $profile = check_user($request->header('profile'));
        if ($profile) {

            $documents = Documents::where('profile_id', $profile->id)->get();
            $urlFile = url("image/documents/");

            return response()->json(['data' => $documents, 'urlFile' => $urlFile]);
        }
        return response()->json(['message' => 'Please Choose Profile'], 404);
    }



    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->profileBusiness->is_approval) {
            $documents = Documents::where("profile_id", $user->profile_business_id)->first();
            $rules = [
                'civil_id' => 'nullable|mimes:jpeg,png,jpg,gif,svg,jfif,csv,txt,xlx,xls,pdf|max:2048',
                'civil_id_back' => 'nullable|mimes:jpeg,png,jpg,gif,svg,jfif,csv,txt,xlx,xls,pdf|max:2048',
                'bank_account_letter' => 'nullable|mimes:jpeg,png,jpg,gif,svg,jfif,csv,txt,xlx,xls,pdf|max:2048',
                'other' => 'nullable|mimes:jpeg,png,jpg,gif,svg,jfif,csv,txt,xlx,xls,pdf|max:2048',
            ];
            $requests = [
                'profile_id' => $user->profile_business_id,
                'civil_id' => $request->civil_id,
                'civil_id_back' => $request->civil_id_back,
                'bank_account_letter' => $request->bank_account_letter,
                'other' => $request->other
            ];

            $data = Validator::make($requests, $rules);
            if ($data->fails()) {
                return response()->json($data->errors(), 404);
            }

            $requests['civil_id'] = $request->civil_id ? 'civil_id_' .  getdate()['year'] . getdate()['yday'] . time() . '.' . $request->civil_id->extension() :
                $civil_id = isset($documents->civil_id) ? $documents->civil_id : null;
            $request->hasFile("civil_id") ? $request->file("civil_id")->storeAs("public/images/documents/", $requests['civil_id']) : null;

            $requests['civil_id_back'] = $request->civil_id_back ? 'civil_id_back_' .  getdate()['year'] . getdate()['yday'] . time() . '.' . $request->civil_id_back->extension() :
                $civil_id_back = isset($documents->civil_id_back) ? $documents->civil_id_back : null;
            $request->hasFile("civil_id_back") ? $request->file("civil_id_back")->storeAs("public/images/documents/", $requests['civil_id_back']) : null;


            $requests['bank_account_letter'] = $request->bank_account_letter ? 'bank_account_letter_' .  getdate()['year'] . getdate()['yday'] . time() . '.' . $request->bank_account_letter->extension() :
                $bank_account_letter = isset($documents->bank_account_letter) ? $documents->bank_account_letter : null;
            $request->hasFile("bank_account_letter") ? $request->file("bank_account_letter")->storeAs("public/images/documents/", $requests['bank_account_letter']) : null;

            $requests['other'] = $request->other ? 'other_' .  getdate()['year'] . getdate()['yday'] . time() . '.' . $request->other->extension() :
                $other = isset($documents->other) ? $documents->other : null;
            $request->hasFile("other") ? $request->file("other")->storeAs("public/images/documents/", $requests['other']) : null;

           $documents = Documents::updateOrCreate(
                ['profile_id' => $user->profile_business_id],
                $requests
            );
            
            $api = url("api/documents");
            $text = "Profile {$user->profileBusiness->company_name} store documents for his profile";
            $this->notificationServiceClass->adminNotification($documents->id, $documents->profile_id, $text, $api, 'documents' , $user->id);
            return response()->json(['message' => 'success']);
        }
        return response()->json(['message' => 'This Profile Is Approval'], 404);
    }
}
