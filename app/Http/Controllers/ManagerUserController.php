<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\AboutStore;
use App\Models\ApiKey;
use App\Models\MultAuth;
use App\Models\ProfileBusiness;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserToken;
use App\Models\Wallet;
use App\Services\NotificationService;
use App\Services\OtpService;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule as ValidationRule;
use PharIo\Manifest\Url;
use Twilio\Rest\Client;



class ManagerUserController extends Controller
{

    private $UserService;
    private $otpService;
    private $notificationServiceClass;
    public function __construct(UserService $UserService, OtpService $otpService, NotificationService $notificationService)
    {
        $this->UserService = $UserService;
        $this->notificationServiceClass = $notificationService;
        $this->otpService = $otpService;
    }

    public function login(LoginRequest $request)
    {
        $credentials = request(['email', 'password']);
        if ($token = auth()->claims(['role_type' => 'user', 'ip' => request()->ip()])->attempt($credentials)) {
            $user = auth()->user();
            // if ($user->confirm_email == true) {

            if ($user->multAuth === null) {
                //
                return $this->respondWithToken($token);
            } else {
                if ($user->multAuth->type) {
                    return  $this->userToken($token);
                }

                return $this->respondWithToken($token);
            }
        } else if ($token = auth()->guard('admin')->claims(['role_type' => 'admin', 'ip' => request()->ip()])->attempt($credentials)) {

            return $this->respondWithToken($token);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }


    public function me()
    {
        if ($admin = auth()->guard('admin')->user()) {
            return response()->json($admin);
        }
        $user = auth()->user();
        $user->makeHidden(['profile_business_id', 'phone_number_code_manager_id', 'nationality_id', 'role_id']);
        $user->phoneNumberCode;
        $user->nationality;
        $user->userRole;
        $user->profileBusiness;
        $user->multAuth->makeHidden('otp');

        return response()->json($user);
    }

    public function logout()
    {
        try {
            auth()->logout();

            return response()->json(['message' => 'Successfully logged out']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }


    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'role_type' => $role_type,
            'expires_in' => Auth::factory()->getTTL() * 60,
            'multiAuth' => false,

        ]);
    }


    public function register(RegisterRequest $request)
    {
        if (isset($request->rules()['message'])) {
            return response()->json($request->rules()['message'], 404);
        }
        $request = $request->toArray();
        $request['language_id'] = 1;

        DB::beginTransaction();

        $profileBusiness = ProfileBusiness::create($request);
        $user = $this->UserService->store($profileBusiness->id);
        // return $user->email;
        Wallet::create([
            'profile_id' => $profileBusiness->id,
            'total_balance' => 0,
            'awating_transfer' => 0,
            'transfered' => 0,
        ]);

        ApiKey::create([
            'token' => Str::random(255),
            'profile_id' => $profileBusiness->id
        ]);
        AboutStore::create([
            'profile_id' => $profileBusiness->id,
            'title' => $profileBusiness->company_name,
            'description' => "Welcome, is $profileBusiness->company_name store ",
            'logo' => null
        ]);
        MultAuth::create([
            'id_admin_or_user' => $user->id,
            'type' => 0,
            'otp' => 0,
            'is_admin' => 0,
        ]);


        DB::commit();
        if ($user) {
            $api = url("admin/admin_profile/show/$profileBusiness->id");
            $text = "New Profile {$profileBusiness->company_name} Created";
            $this->notificationServiceClass->adminNotification($profileBusiness->id, $profileBusiness->id, $text, $api, 'profiles', $user->id);
            // return $this->otpService->createEmail($user->email, $user->id);
            return response()->json(['message' => 'Success']);
        }
        return response()->json([
            'message' => 'Register failed'
        ], 404);
    }



    public function changePassword(Request $request)
    {

        $data = Validator::make($request->all(), [
            'old_password' => 'required|string|max:20', // password_confirmation
            'new_password' => 'required|confirmed|string|max:20', // password_confirmation

        ]);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'The old password is incorrect'
            ], 404);
        }

        User::find($user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'message' => 'sucess',
        ]);
    }

    public function userToken($token)
    {
        $user = auth()->user();
        UserToken::updateOrCreate(
            [
                'user_id' => $user->id,
                'ipUser' => request()->ip()
            ],
            [
                'user_id' => $user->id,
                'token' => $token,
                'ipUser' => request()->ip()
            ]
        );
        if ($user->multAuth->type === 1) {
            if ($this->otpService->createSms($user->phoneNumberCode->code . $user->phone_number_manager, $user->id, 0)) {
                return response()->json([
                    'message' => $this->otpService->createSms($user->phoneNumberCode->code . $user->phone_number_manager, $user->id, 0),
                ], 404);
            } else {
                return response()->json([
                    'message' => 'check your Sms to verification ',
                    'multiAuth' => true,
                ]);
            }
        } else if ($user->multAuth->type === 2) {

            if ($this->otpService->createEmail($user->email, $user->id, 0)) {
                return response()->json([
                    'message' => $this->otpService->createEmail($user->email, $user->id, 0),
                ], 404);
            } else {
                return response()->json([
                    'message' => 'check your email to verification ',
                    'multiAuth' => true,
                ]);
            }
        }
    }


    public function verify(Request $request)
    {
        $validation = Validator::make(request()->all(), [
            'verification_code' => ['required', 'numeric'],
            'email' => ['required', 'email', 'exists:users,email'],
        ]);
        if ($validation->fails()) {
            return response($validation->errors(), 404);
        }

        $user = User::where('email', $request->email)->select('id', 'phone_number_code_manager_id', 'phone_number_manager', 'email')
            ->with(['phoneNumberCode', 'multAuth', 'userToken' => function ($q) {
                $q->where('ipUser', request()->ip());
            }])->first();

        $data['verification_code'] = request()->verification_code;

        if ($user->multAuth->type == 1) {
            $otp = $user->phoneNumberCode->code . $user->phone_number_manager;
        } else if ($user->multAuth->type == 2) {
            $otp = $user->email;
        }
        try {
            /* Get credentials from .env */
            $verification = $this->verificationChecks($otp, $data['verification_code']);
            if ($verification->valid) {
                return $this->respondWithToken($user->userToken->token);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }



    public function verificationChecks($otp, $verification_code)
    {
        // $token = getenv("TWILIO_AUTH_TOKEN");
        // $twilio_sid = getenv("TWILIO_SID");
        // $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        // $token = '2ad973ab2c62499b8fd279d525221d72';
        // $twilio_sid = 'ACc2673d7b5bd9b643bcdf18e6e7b9889a';
        // $twilio_verify_sid = 'VA66326fb9bf5bd19b336528561b7ed0c5';

        // This Twillo abdo salah
        $token = 'b8aeab50e2075d01d93b531082106d8e';
        $twilio_sid = 'AC1feea77843cfcaf5f2dbb420e65842fc';
        $twilio_verify_sid = 'VA069954f568fb58761daf70c946f02e83';
        $twilio = new Client($twilio_sid, $token);

        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create(
                [
                    "to" => $otp,
                    "code" => $verification_code,
                ]
            );
        return $verification;
    }


    public function multiFactotrAuth(Request $request)
    {
        $user = auth()->user();
        $data = Validator::make($request->all(), [
            'type' => 'required|integer|max:2|min:0', // password_confirmation
        ]);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }
        if ($request->type == 1 and !$user->confirm_phone) {
            return response()->json(['message' => 'This Phone Not Confirm'], 404);
        }
        MultAuth::updateOrCreate(
            ['id_admin_or_user' => $user->id],
            [
                'id_admin_or_user' => $user->id,
                'type' => $request->type,
            ]
        );
        return response()->json(['message' => 'success']);
    }



    public function verifyOtp(Request $request)
    {
        $validation = Validator::make(request()->all(), [
            'verification_code' => ['required', 'numeric'],
            'email' => ['required', 'email', 'exists:users,email'],
        ]);
        if ($validation->fails()) {
            return response($validation->errors(), 404);
        }

        $user = User::where('email', $request->email)->select('id', 'email', 'confirm_email')
            ->with(['multAuth' => function ($q) {
                $q->where('is_admin', 0);
            }])->with(['userToken' => function ($q) {
                $q->where('ipUser', request()->ip());
            }])->first();

        if (!$user->confirm_email and $request->verification_code == $user->multAuth->otp and $user->multAuth->updated_at->addMinutes(10) >= Carbon::now()) {

            $user->update([
                'confirm_email' => true
            ]);
            return response()->json(['message' => 'Email Confirmed Success']);
        } elseif ($user->multAuth->type == 2 and $request->verification_code == $user->multAuth->otp and $user->multAuth->updated_at->addMinutes(10) >= Carbon::now()) {
            return $this->respondWithToken($user->userToken->token);
        } elseif ($user->multAuth->type == 1) {
            $isVerified = $this->otpService->verifyOTP($user->multAuth->otp, $request->verification_code);
            if ($isVerified) {
                return $this->respondWithToken($user->userToken->token);
            }
        }
        return response()->json(['message' => 'OTP verification failed'], 400);
    }
}
