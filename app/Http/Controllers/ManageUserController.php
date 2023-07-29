<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreManageUserRequest;
use App\Http\Requests\UpdateManageUserRequest;
use App\Models\Admin;
use App\Models\MultAuth;
use App\Models\PasswordReset;
use App\Models\setting\MailSenderInformation;
use App\Models\User;
use App\Services\ManageUserService;
use App\Services\OtpService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Twilio\Rest\Client;



class ManageUserController extends Controller
{
    private $manageUser;
    private $UserService;
    private $otpService;
    public function __construct(ManageUserService $manageUser, UserService $UserService, OtpService $otpService)
    {
        $this->manageUser = $manageUser;
        $this->UserService = $UserService;
        $this->otpService = $otpService;
    }




    public function index(Request $request)
    {
        $profile = check_user($request->header('profile'));
        if ($profile) {

            $users = User::where('profile_business_id', $profile->id)
                ->with('nationality')->with('userRole')->get();

            return response()->json(['data' => $users]);
        }
        return response()->json(['message' => 'Please Choose Profile'], 404);
    }

    public function show(Request $request, $id)
    {
        $profile = check_user($request->header('profile'));
        if ($profile) {
            $manageuser = User::where('profile_business_id', $profile->id)
                ->with('nationality')->with('userRole')->find($id);

            return $manageuser ? response()->json(['data' => $manageuser]) :
                response()->json(['message' => 'this manage user is not found or deleted'], 404);
        }
        return response()->json(['message' => 'Please Choose Profile'], 404);
    }

    public function store(StoreManageUserRequest $request)
    {
        $user = auth()->user();

        if (isset($request->rules()['message'])) {
            return response()->json($request->rules()['message'], 404);
        }

        $req = $this->manageUser->manageUser();

        $user = User::create($req);

        $dateTime =  Carbon::now()->format('Y-m-d H:i:s');
        MultAuth::create([
            'id_admin_or_user' => $user->id,
            'type' => 0,
            'otp' => 0,
            'is_admin' => 0,
        ]);

        return response()->json([
            'message' =>
            [
                "manager user created successed",
                'check your email',
            ]
        ]);
    }
    public function update(UpdateManageUserRequest $request, $id)
    {

        if (isset($request->rules()['message'])) {
            return response()->json($request->rules()['message'], 404);
        }


        $user = auth()->user();
        $manageUser = User::findOrFail($id);
        if ($user->profile_business_id == $manageUser->profile_business_id) {

            $req = $this->manageUser->manageUser($id);

            $manageUser->update($req);
            return response()->json([
                'message' => "manager user updated successed",
            ]);
        } else {
            return response()->json([
                'message' => "you can not update this user",
            ], 404);
        }
    }
    public function delete($id)
    {
        $user = auth()->user();
        $manageuser = User::find($id);
        if ($manageuser) {
            if ($manageuser->profile_business_id == $user->profile_business_id) {
                $manageuser->delete();
                return response()->json([
                    "message" => "Success deleted"
                ]);
            }
        }
        return response()->json([
            'message' => 'You do not have permission to delete this user'
        ], 404);
    }


    public function forgetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'sender' => 'required',
            'type' => 'required|integer|min:1|max:2',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        if ($request->type == 1) {
            $user = User::where('phone_number_manager', $request->sender)->with('phoneNumberCode')->first();
            $admin = Admin::where('phone', $request->sender)->with('phoneNumberCode')->first();
            if ($user and $user->confirm_phone == 1) {
                $id = $user->id;
                $is_admin = 0;
                $sender = $user->phoneNumberCode->code . $request->sender;
            } elseif ($admin) {
                $id = $admin->id;
                $is_admin = 1;
                $sender = $admin->phoneNumberCode->code . $request->sender;
            } else {
                return response()->json(['message' => ' This Phone is not found or confirm it first'], 404);
            }
        } else {

            $user = User::where('email', $request->sender)->first();
            $admin = Admin::where('email', $request->sender)->first();

            $sender = $request->sender;

            if ($user and $user->confirm_email == 1) {
                $id = $user->id;
                $is_admin = 0;
            } elseif ($admin) {
                $id = $admin->id;
                $is_admin = 1;
            } else {
                return response()->json(['message' => 'This Email is not found or confirm it first'], 404);
            }
        }


        $dateTime =  Carbon::now()->format('Y-m-d H:i:s');
        PasswordReset::create([
            'sender' => $sender,
            'type' => $request->type,
            'created_at' => $dateTime
        ]);
        if ($request->type == 1) {
            if (!$this->otpService->createSms($sender, $id, $is_admin)) {
                return response()->json(['message' => 'check your sms to reset your password']);
            } else {
                return response()->json(['message' => $this->otpService->createSms($sender, $id, $is_admin)], 404);
            }
        } else {
            if (!$this->otpService->createEmail($sender, $id, $is_admin)) {
                return response()->json(['message' => 'check your Email to reset your password']);
            } else {
                return response()->json(['message' => $this->otpService->createEmail($sender, $id, $is_admin)], 404);
            }
        }
    }

    public function verify(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'nullable|min:8|max:20|string',
                'sender' => 'required|string'
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        $user = User::where('email', $request->sender)->orWhere('phone_number_manager', $request->sender)
            ->with(['multAuth' => function ($q) {
                $q->where('is_admin', 0);
            }])->first();
            
        $admin = Admin::where('email', $request->sender)->orWhere('phone', $request->sender)
            ->with(['multAuth' => function ($q) {
                $q->where('is_admin', 1);
            }])->first();
        if ($user) {
            if (!$user->confirm_email) {
                $user->update(['confirm_email' => true]);
            }
            $resetDataUser = PasswordReset::Where('sender', $user->phoneNumberCode->code . $request->sender)->where('type', 1)
                ->orWhere('sender', $request->sender)->where('type', 2)
                ->first();
        } elseif ($admin) {
            $resetDataAdmin = PasswordReset::Where('sender', $admin->phoneNumberCode->code . $request->sender)->where('type', 1)
                ->orWhere('sender', $request->sender)->where('type', 2)
                ->first();
        } else {
            return response()->json(['message' => 'Not This user'], 404);
        }

        if (isset($resetDataUser)) {
            if ($resetDataUser->type == 1) {
                $verification = $this->otpService->verifyOTP($user->multAuth->otp, $request->verification_code);
                if ($verification) {
                    return  $this->resetPassword($request, $resetDataUser);
                } else {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            } elseif ($resetDataUser->type == 2) {
                if ($request->verification_code == $user->multAuth->otp and $user->multAuth->updated_at->addMinutes(10) >= Carbon::now()) {
                    return  $this->resetPassword($request, $resetDataUser);
                } else {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            }
        } else if (isset($resetDataAdmin)) {

            if ($resetDataAdmin->type == 1) {

                $verification = $this->otpService->verifyOTP($admin->multAuth->otp, $request->verification_code);
                if ($verification) {
                    return  $this->resetPassword($request, $resetDataAdmin);
                } else {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            } elseif ($resetDataAdmin->type == 2) {

                if ($request->verification_code == $admin->multAuth->otp and $admin->multAuth->updated_at->addMinutes(10) >= Carbon::now()) {
                    return $this->resetPassword($request, $resetDataAdmin);
                } else {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
            }
        }
        return response()->json(['message' => 'This Not Found'], 404);
    }


    public function resetPassword($request, $resetData)
    {

        $user = User::where('email', $request->sender)->orWhere('phone_number_manager', $request->sender)->first();
        $admin = Admin::where('email', $request->sender)->orWhere('phone', $request->sender)->first();

        if ($resetData) {
            if (Carbon::parse($resetData->created_at) < Carbon::now()->addMinute(5)) {

                if ($user) {
                    $user->password = bcrypt($request->password);
                    $user->update([
                        'password' => $user->password
                    ]);
                } elseif ($admin) {
                    $admin->password = bcrypt($request->password);
                    $admin->update([
                        'password' => $admin->password
                    ]);
                }

                $resetData->delete();

                return response()->json(['message' => ' your password reset success']);
            }
            $resetData->delete();
            return response()->json(['message' => 'this link is expiry'], 404);
        }

        return response()->json(['message' => ' this user not found'], 404);
    }

    public function verificationChecks($otp, $verification_code)
    {
        // $token = getenv("TWILIO_AUTH_TOKEN");
        // $twilio_sid = getenv("TWILIO_SID");
        // $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");

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
}
