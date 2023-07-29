<?php

namespace App\Http\Controllers\Admin\setting;

use App\Http\Controllers\Controller;
use App\Models\setting\PaymentMethod;
use App\Services\UpdateFileService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PaymentMethodController extends Controller
{

    private $updateFileService;
    function __construct(UpdateFileService $updateFileService)
    {
        $this->updateFileService = $updateFileService;
    }
    public function index()
    {
        $payment_methods = PaymentMethod::with('commissionPaymentMethod')->get();
        // return $payment_methods;
        // return  $payment_methods[0]->commissionPaymentMethod->sum('commission');
        $urlFile = url("image/payment_method");

        return response()->json(['data' => $payment_methods, 'urlFile' => $urlFile]);
    }
    public function store(Request $request)
    {
        $req = [
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'logo' => $request->logo,
            'commission_bank' => $request->commission_bank,
            'commission_safqa' => $request->commission_safqa,
            'is_active' => $request->is_active ? true : false,
        ];

        // if (!$request->is_active) {
        //     $req['is_active'] = false;
        // } else
        //     $req['is_active'] = true;

        $rules = [
            'name_en' => 'string|required|unique:payment_methods,name_en',
            'name_ar' => 'string|required|unique:payment_methods,name_ar',
            'logo' => 'required|mimes:jpeg,png,jpg|max:1024',
            'is_active' => 'boolean|required',
            'commission_bank' => 'integer|required',
            'commission_safqa' => 'integer|required',
        ];
        $data = Validator::make($req, $rules);
        if ($data->fails()) {
            return response()->json($data->errors(), 404);
        }

        if ($request->logo) {
            $logo = getdate()['year'] . getdate()['yday'] . time() . '.' . $request->logo->extension();
            $request->file("logo")->storeAs("public/images/paymentMethod/logo", $logo);
            $req['logo'] = $logo;
        }
        PaymentMethod::create($req);

        return response()->json(['message' => 'success']);
    }

    public function show($id)
    {
        $payment_method = PaymentMethod::find($id);
        return  $payment_method ?  response()->json(['data' => $payment_method])
            : response()->json(['message' => 'this payment is not found'], 404);
    }

    public function update(Request $request, $id)
    {

        $payment_method = PaymentMethod::find($id);
        $pathOldImage  = storage_path("app/public/images/paymentMethod/logo/$payment_method->logo");

        if ($payment_method) {
            $req = [
                'name_en' => $request->name_en,
                'name_ar' => $request->name_ar,
                'logo' => $request->logo,
                'is_active' => $request->is_active ? true : false,
            ];

            // if (!$request->is_active)
            //     $req['is_active'] = false;
            // else
            //     $req['is_active'] = true;

            $rules = [
                'name_en' => "string|required|unique:payment_methods,name_en,$id",
                'name_ar' => "string|required|unique:payment_methods,name_ar,$id",
                'logo' => 'nullable|mimes:jpeg,png,jpg|max:1024',
                'is_active' => 'boolean|required',
            ];

            $data = Validator::make($req, $rules);

            if ($data->fails())
                return response()->json($data->errors(), 404);

            if ($request->logo) {
                $logo = getdate()['year'] . getdate()['yday'] . time() . '.' . $request->logo->extension();
                $req['logo'] = $logo;
            } else
                $req['logo'] = $payment_method->logo;

            $payment_method->update($req);

            if ($request->logo) {
                $request->file("logo")->storeAs("public/images/paymentMethod/logo", $logo);
                if (File::exists($pathOldImage)) {
                    unlink($pathOldImage);
                }
            }

            return response()->json(['message' => 'success']);
        } else
            return response()->json(['message' => 'this payment is not found'], 404);
    }

    public function delete($id)
    {
        $payment_method = PaymentMethod::find($id);

        if ($payment_method) {
            $payment_method->delete($id);
            $requestData['logo'] = $this->updateFileService->deleteFile("/public/images/paymentMethod/logo/$payment_method->logo");
            return response()->json(['message' => 'success']);
        } else
            return response()->json(['message' => 'this payment is not found'], 404);
    }
}
