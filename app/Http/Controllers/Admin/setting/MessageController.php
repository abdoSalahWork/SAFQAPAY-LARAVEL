<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\Message;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class MessageController extends Controller
{

    private $notificationServiceClass;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationServiceClass = $notificationService;
    }


    public function index()
    {
        $messages = Message::with('support_type')->get();
        $imageUrl = url('image/message');

        return response()->json(['data' => $messages, $imageUrl]);
    }
    public function show($id)
    {
        $message = Message::with('support_type')->find($id);
        $message['image_file'] = url("image/message/$message->image_file");
        return $message ? response()->json(['data' => $message]) :
            response()->json(['message' => "message not found"], 404);
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        $codePhone = User::with('phoneNumberCode')->find($user->id);
        $rules = [
            'support_type_id' => 'required|integer|exists:support_types,id',
            'message' => 'required|string',
            'image_file' => 'nullable|mimes:jpeg,png,jpg,gif,svg,jfif,csv,txt,xlx,xls,pdf|max:2048',
        ];
        $req = [
            'profile_id' => $user->profile_business_id,
            'user_id' => $user->id,
            'full_name' => $user->full_name,
            'mobile' => $codePhone->phoneNumberCode->code . $user->phone_number_manager,
            'email' => $user->email,
            'support_type_id' => $request->support_type_id,
            'message' => $request->message,
            'image_file' => $request->image_file
        ];

        $data = Validator::make($req, $rules);
        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }
        if ($request->image_file) {
            $image_file_name = getdate()['year'] . getdate()['yday'] . time() . '.' . $request->image_file->extension();
            $request->file("image_file")->storeAs("public/images/message/", $image_file_name);
            $req['image_file'] = $image_file_name;
        }
        $message = Message::create($req);
        
        $api = url("admin/message/show/$message->id");
        $text = "Message {$message->id} send from profile {$message->profileBusiness->company_name}";
        $this->notificationServiceClass->adminNotification($message->id, $message->profile_id, $text, $api, 'Messages', $user->id);
        return response()->json(['message' => 'success']);
    }


    public function delete($id)
    {

        $message = Message::find($id);
        if ($message) {
            $message->delete();
            return response()->json(['message' => "deleted succes"]);
        }
        return response()->json(['message' => "message not found"], 404);
    }
}
