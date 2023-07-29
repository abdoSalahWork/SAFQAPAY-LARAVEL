<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\MultAuth;
use App\Services\ManageUserService;
use App\Services\NotificationService;
use App\Services\SendMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{

    private $sendMailService;
    private $manageUserService;
    private $notificationServiceClass;

    public function __construct(SendMailService $sendMailService, ManageUserService $manageUserService ,NotificationService $notificationService)
    {
        $this->sendMailService = $sendMailService;
        $this->manageUserService = $manageUserService;
        $this->notificationServiceClass = $notificationService;

    }

    public function index()
    {
        $admins = Admin::get();
        return response()->json(['data' => $admins]);
    }
    public function show($id)
    {
        $admin = Admin::with('multAuth')->findOrFail($id);
        return response()->json(['data' => $admin]);
    }
    public function store(Request $request)
    {

        $validateData = [
            'is_super_admin' => 'required|boolean',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email|unique:users,email',
            'phone' => 'required|string|max:20|min:5|unique:admins,phone|unique:users,phone_number_manager',
            'phone_number_code_id' => 'required|integer|exists:countries,id,country_active,1',
            // premission
            'wallet' => 'boolean',
            'admins' => 'boolean',
            'profiles' => 'boolean',
            'invoices' => 'boolean',
            'refunds' => 'boolean',
            'addresses' => 'boolean',
            'languages' => 'boolean',
            'banks' => 'boolean',
            'business_categories' => 'boolean',
            'business_types' => 'boolean',
            'payment_methods' => 'boolean',
            'social_media' => 'boolean',

        ];
        $requestData = [
            'is_super_admin' => $request->is_super_admin,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'password' => Hash::make($request->password),
            // 'password' => Hash::make(Str::random(255)),
            'phone_number_code_id' => $request->phone_number_code_id,

            //premission
            'wallet' => request()->wallet ? true : false,
            'profiles' => request()->profiles ? true : false,
            'admins' => request()->admins ? true : false,
            'invoices' => request()->invoices ? true : false,
            'refunds' => request()->refunds ? true : false,
            'addresses' => request()->addresses ? true : false,
            'languages' => request()->languages ? true : false,
            'banks' => request()->banks ? true : false,
            'business_categories' => request()->business_categories ? true : false,
            'business_types' => request()->business_types ? true : false,
            'payment_methods' => request()->payment_methods ? true : false,
            'social_media' => request()->social_media ? true : false,

        ];

        $data = Validator::make($requestData, $validateData);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }


        if ($request->is_super_admin == 1) {
            $admins =  Admin::get();
            foreach ($admins as $admin) {
                $admin->update([
                    'is_super_admin' => false
                ]);
            }
        }

        if (auth()->guard('admin')->user()->is_super_admin == 1) {
            DB::beginTransaction();
            $admin =  Admin::create($requestData);
            $multiAuth = MultAuth::create([
                'id_admin_or_user' => $admin->id,
                'type' => false,
                'otp' => false,
                'is_admin' => true,
            ]);
            DB::commit();


            $this->manageUserService->sendEmailToResetPassword($request->name, $request->email);

            $api = url("admin/show/$admin->id");
            $text = "New Admin {$admin->name} Created";
            $this->notificationServiceClass->adminNotification($admin->id, $admin->id, $text, $api, 'admins' , $admin->id);
            return response()->json([
                'message' =>
                [
                    "admin created successed",
                    'check email',
                ]
            ]);
        }
        return response()->json(['message' => "you can not create admin"], 404);
    }
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $validtion = Validator::make(request()->all(), [
            'is_super_admin' => 'required|boolean',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|min:5|unique:admins,phone,' . $id,

            'phone_number_code_id' => 'required|integer|exists:countries,id,country_active,1',
            // premission
            'wallet' => 'boolean',
            'admins' => 'boolean',
            'profiles' => 'boolean',
            'invoices' => 'boolean',
            'refunds' => 'boolean',
            'addresses' => 'boolean',
            'languages' => 'boolean',
            'banks' => 'boolean',
            'business_categories' => 'boolean',
            'business_types' => 'boolean',
            'payment_methods' => 'boolean',
            'social_media' => 'boolean',
        ]);
        if ($validtion->fails()) {
            return response()->json($validtion->errors(), 404);
        }
        $requestData = [
            'is_super_admin' => $admin->is_super_admin,
            'name' => $admin->name,
            'email' => $admin->email,
            'phone' => $admin->phone,
            'phone_number_code_id' => $admin->phone_number_code_id,

            //premissions
            'wallet' => $request->wallet ? true : false,
            'admins' => $request->admins ? true : false,
            'invoices' => $request->invoices ? true : false,
            'refunds' => $request->refunds ? true : false,
            'addresses' => $request->addresses ? true : false,
            'languages' => $request->languages ? true : false,
            'banks' => $request->banks ? true : false,
            'business_categories' => $request->business_categories ? true : false,
            'business_types' => $request->business_types ? true : false,
            'payment_methods' => $request->payment_methods ? true : false,
            'social_media' => $request->social_media ? true : false,
            'profiles' => $request->profiles ? true : false,
        ];
        if (auth()->guard('admin')->user()->is_super_admin == 1) {

            $admin->update($requestData);
            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'this is super admin you can not update on it'
        ], 404);
    }
    public function delete($id)
    {
        $admin = Admin::with('MultAuth')->findOrFail($id);

        if (auth()->guard('admin')->user()->is_super_admin == 1 and $admin->is_super_admin == 0) {

            $admin->delete();
            $admin->MultAuth->delete();
            return response()->json([
                'message' => 'sucsess'
            ]);
        }
        return response()->json([
            'message' => 'this is super admin you can not delete on it'
        ], 404);
    }
}
