<?php

namespace App\Http\Controllers;

use App\Models\AccountStatement;
use App\Models\Commission;
use App\Models\MoneyRequest;
use App\Models\Wallet;
use App\Models\WalletAdmin;
use App\Services\ConvertCurrencyService;
use App\Services\NotificationService;
use App\Services\PercentagePaymentService;
use App\Services\SendMailService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class MoneyRequestController extends Controller
{
    private $convertCurrencyServiceClass;

    private  $notificationServiceClass, $sendMailService, $PercentagePaymentServiceClass, $WalletServiceClass;


    public function __construct(NotificationService $notificationService, SendMailService $sendMailService, ConvertCurrencyService $convertCurrencyService, PercentagePaymentService $PercentagePaymentService, WalletService $WalletService)
    {
        $this->notificationServiceClass = $notificationService;
        $this->sendMailService = $sendMailService;
        $this->convertCurrencyServiceClass = $convertCurrencyService;
        $this->PercentagePaymentServiceClass = $PercentagePaymentService;
        $this->WalletServiceClass = $WalletService;
    }


    //
    public function index()
    {
        $user = auth()->user();
        $deposits =  MoneyRequest::where('profile_id', $user->profile_business_id)->get();
        $totalDeposits = $deposits->sum('amount');
        return response()->json(['data' => $deposits, 'total_deposits' => $totalDeposits]);
    }


    public function store(Request $request)
    {
        $user = auth()->user();
        $wallet = Wallet::where('profile_id', $user->profile_business_id)->firstOrFail();
        $amountBetween = $wallet->total_balance - $wallet->awating_transfer - 0.01;
        $validator = Validator::make(
            ['amount' => $request->amount],
            ['amount' => "required|numeric|between:1,$amountBetween"]
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        $wallet_total_balance_USD = $this->convertCurrencyServiceClass->convartCurrencyWalletToUSA($user->profileBusiness->country->short_currency, ($wallet->total_balance - $wallet->awating_transfer));

        if ($wallet_total_balance_USD < 50) {
            return response()->json(['message' => 'your wallet is less than 50 $'], 404);
        }

        $amount_USD = $this->convertCurrencyServiceClass->convartCurrencyWalletToUSA($user->profileBusiness->country->short_currency, $request->amount);

        if ($amount_USD < 50) {
            return response()->json(['message' => 'your amount is less than 50 $'], 404);
        }
        // return $amount_USD;


        $awating_transfer = $wallet->awating_transfer + $request->amount;
        $awating_transfer_dollers = $wallet->awating_transfer_doller + $amount_USD;

        DB::beginTransaction();

        $moneyRequest = MoneyRequest::create([
            'profile_id' => $user->profile_business_id,
            'amount' => $request->amount,
            'user_id' => $user->id,
            'type' => 'money_request',
        ]);

        $wallet->update([
            'awating_transfer' => $awating_transfer,
            'awating_transfer_doller' =>  $awating_transfer_dollers

        ]);
        DB::commit();
        // $this->notificationDeposit($moneyRequest->id ,$user->profile_business_id ,$user->full_name );

        //admin notification
        $api = url("admin/money_requests");
        $text = "New withdrawal {$moneyRequest->id} Created from profile {$user->profileBusiness->company_name} ";
        $this->notificationServiceClass->adminNotification($moneyRequest->id, $moneyRequest->profile_id, $text, $api, 'wallet', $user->id);

        return response()->json(['message' => 'success']);
    }

    public function update(Request $request, $request_money_id)
    {
        $user = auth()->user();
        $validator = Validator::make(
            ['amount' => $request->amount],
            ['amount' => 'required']
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        $wallet = Wallet::where('profile_id', $user->profile_business_id)->firstOrFail();
        $wallet_total_balance_USD = $this->convertCurrencyServiceClass->convartCurrencyWalletToUSA($user->profileBusiness->country->short_currency, ($wallet->total_balance - $wallet->awating_transfer));

        if ($wallet_total_balance_USD < 50) {
            return response()->json(['message' => 'your wallet is less than 50 $'], 404);
        } elseif ($wallet->total_balance <  $wallet->awating_transfer + $request->amount) {
            return response()->json(['message' => 'It is not possible because the requested amount is greater than the total balance'], 404);
        }


        $amount_USD = $this->convertCurrencyServiceClass->convartCurrencyWalletToUSA($user->profileBusiness->country->short_currency, $request->amount);
        if ($amount_USD < 50) {
            return response()->json(['message' => 'your amount is less than 50 $'], 404);
        }


        $moneyRequest = MoneyRequest::where('profile_id', $user->profile_business_id)->find($request_money_id);
        if ($moneyRequest and $moneyRequest->status == 'pending') {

            $wallet = Wallet::where('profile_id', $user->profile_business_id)->firstOrFail();
            if ($request->amount  < $wallet->total_balance) {


                DB::beginTransaction();
                $moneyRequest_amount_usd = $this->convertCurrencyServiceClass->convartCurrencyWalletToUSA($user->profileBusiness->country->short_currency, $moneyRequest->amount);


                $wallet->update([
                    'awating_transfer' => $wallet->awating_transfer - $moneyRequest->amount + $request->amount,
                    'awating_transfer_doller' => $wallet->awating_transfer_doller - $moneyRequest_amount_usd + $amount_USD

                ]);
                $moneyRequest->update([
                    'amount' =>  $request->amount,

                ]);
                DB::commit();

                return response()->json(['message' => 'success']);

                // $moneyRequest->
            }
        } else {
            return response()->json(['message' => 'you can not update on this request'], 404);
        }
    }

    public function cancel($id)
    {
        $user = auth()->user();
        $money_request = MoneyRequest::where('profile_id', $user->profile_business_id)
            ->where('status', 'pending')->orWhere('status', 'processing')
            ->find($id);

        if ($money_request) {
            $wallet = Wallet::where('profile_id', $money_request->profile_id)->firstOrFail();

            $moneyRequest_amount_usd = $this->convertCurrencyServiceClass->convartCurrencyWalletToUSA($user->profileBusiness->country->short_currency, $money_request->amount);


            $wallet->awating_transfer -= $money_request->amount;
            $wallet->awating_transfer_doller -= $moneyRequest_amount_usd;

            // $wallet->total_balance -= $money_request->amount;
            $wallet->update([
                'awating_transfer' => $wallet->awating_transfer,
                'awating_transfer_doller' => $wallet->awating_transfer_doller

                // 'total_balance' => $wallet->total_balance
            ]);
            $money_request->update(['status' => 'unpaid']);
            return response()->json(['message' => 'success']);
        }
        return response()->json(['message' => 'you can not cancel this request']);
    }



    //////// admin /////////////

    public function index_admin()
    {
        // $money_requests =  ProfileBusiness::with('money_request')->get();
        $money_requests =  MoneyRequest::where('type', 'money_request')->with('profile_information.country')->get();
        return response()->json(['data' => $money_requests]);
    }


    public function confirm_admin($money_request_id, Request $request)
    {
        $admin = auth()->guard('admin')->user();
        $money_request = MoneyRequest::where('status', 'pending')->where('id', $money_request_id)
            ->orWhere('status', 'processing')->where('id', $money_request_id)
            ->with('profile_information.country')->firstOrFail();
        $validateData =  [
            'status' => 'required|in:pending,processing,paid,unpaid'
        ];
        $requestData =   [
            'status' => $request->status
        ];

        $data = Validator::make($requestData, $validateData);

        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        $wallet = Wallet::where('profile_id', $money_request->profile_id)->firstOrFail();


        if ($money_request->amount <= $wallet->total_balance) {


            DB::beginTransaction();
            $moneyRequest_amount_usd = $this->convertCurrencyServiceClass->convartCurrencyWalletToUSA($wallet->profile->country->short_currency, $money_request->amount);

            if ($request->status == 'paid') {
                $wallet->awating_transfer -= $money_request->amount;
                $wallet->total_balance -= $money_request->amount;
                $wallet->awating_transfer_doller -= $moneyRequest_amount_usd;
                $wallet->total_balance_doller -= $moneyRequest_amount_usd;


                $wallet->update([
                    'awating_transfer' => $wallet->awating_transfer,
                    'total_balance' => $wallet->total_balance,
                    'awating_transfer_doller' => $wallet->awating_transfer_doller,
                    'total_balance_doller' => $wallet->total_balance_doller,
                ]);
                AccountStatement::create([
                    'profile_id' => $money_request->profile_id,
                    'Description' => 'Withdrawal',
                    'Debit' => $money_request->amount,
                    'Credit' => null,
                    'Balance' => $wallet->total_balance,
                    'reference_number' => $money_request->id
                ]);
                $commission = Commission::first();

                $this->WalletServiceClass->walletSafqa($money_request->amount * ($commission->safqa_commission + $commission->payment_commission), $money_request->profile_information->country->id);
                $money_request->update(['status' => $request->status]);
            } else if ($request->status == 'unpaid') {

                $wallet->awating_transfer -= $money_request->amount;
                $wallet->awating_transfer_doller -= $moneyRequest_amount_usd;

                $wallet->update([
                    'awating_transfer' => $wallet->awating_transfer,
                    'awating_transfer_doller' => $wallet->awating_transfer_doller,
                ]);
                $money_request->update(['status' => $request->status]);
            } else {
                $money_request->update(['status' => $request->status]);
            }

            DB::commit();

            $api = url("admin/money_requests");
            $text = " withdrawal updated for {$request->status} profile {$money_request->profile_information->company_name} from admin $admin->name ";
            $this->notificationServiceClass->adminNotification($money_request->id, $money_request->profile_id, $text, $api, 'wallet', $admin->id);
            return response()->json(['message' => 'success']);
        }
        return response()->json(['message' => 'wallet of the profile amount con not be enough'], 404);
    }

    public function delete_admin($id)
    {
        MoneyRequest::findOrFail($id)->delete();
        return response()->json(['message' => 'success']);
    }

    public function notificationDeposit($money_request_id, $money_request_profile_id, $user_name)
    {

        $domain = URL::to('/');
        $api = "$domain/api/deposits";
        $text = "New Deposit request from $user_name";
        $column = 'notification_deposit';

        $this->notificationServiceClass->notification($money_request_id, $money_request_profile_id, $text, $api, $column);
        $this->sendMailService->send_email_money_request($money_request_id, $money_request_profile_id, $user_name);
    }
}
