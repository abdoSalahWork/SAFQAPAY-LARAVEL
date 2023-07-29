<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice\Invoice;
use App\Services\ConvertCurrencyService;
use App\Services\Invoice\StoreInviceProductService;
use App\Services\Invoice\StoreInvoiceService;
use App\Services\Invoice\StoreQuickInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;
use App\Services\DiscountService;
use App\Services\Invoice\UpdateInvoiceService;
use App\Services\StripeService;

class InvoiceController extends Controller
{

    private $updateInvoiceServiceClass,  $storeInvoiceServiceClass, $storeQuickInvoiceServiceClass, $convertCurrencyServiceClass, $DiscountServiceClass, $storeInviceProductServiceClass, $stripeService;

    public function __construct(
        StoreInvoiceService $storeInvoiceService,
        UpdateInvoiceService $updateInvoiceService,
        ConvertCurrencyService $convertCurrencyService,
        StoreQuickInvoiceService $storeQuickInvoiceService,
        StoreInviceProductService $storeInviceProductService,
        DiscountService $DiscountService,
        StripeService $stripeService


    ) {
        $this->updateInvoiceServiceClass = $updateInvoiceService;
        $this->storeInvoiceServiceClass = $storeInvoiceService;
        $this->storeQuickInvoiceServiceClass = $storeQuickInvoiceService;
        $this->storeInviceProductServiceClass = $storeInviceProductService;
        $this->DiscountServiceClass = $DiscountService;
        $this->convertCurrencyServiceClass = $convertCurrencyService;
        $this->stripeService = $stripeService;
    }

    function index(Request $request)
    {

        $profile = check_user($request->header('profile'));
        if ($profile) {

            $invoices = Invoice::where('profile_business_id', $profile->id)
                ->where('invoice_type', 'invoice')->get();

            $urlFile = url("api/image/invoice/$profile->id");

            return response()->json(['data' => $invoices, 'urlFile' => $urlFile]);
        }
        return response()->json(['message' => 'Please Choose Profile'], 404);
    }

    function show($invoiceId)
    {
        $user = auth()->user();
        $invoice = Invoice::where('profile_business_id', $user->profile_business_id)
            ->with('currency')->with('transcation')->with('vendor')->with('recurring_interval')
            ->with('invoice_item')->with('view')->with('send_invoice_option')->find($invoiceId);

        if ($invoice) {
            if ($invoice->expiry_date < Carbon::now()->toDateString() && $invoice->status == 'pending') {
                $invoice->update(['status' => 'unpaid']);
            }
            $invoice['invoiceUrl'] = $user->profile_business_id . $invoice['manager_user_id'] . $invoice['id'];
            $invoice['attach_file'] = $invoice->attach_file
                ? url("image/invoice/$user->profile_business_id/$invoice->attach_file") : null;
            return response()->json(['data' => $invoice]);
        }
        return response()->json(['message' => 'invoice not found'], 404);
    }

    function store(InvoiceRequest $request)
    {
        if ($request->rules()['message'] != 'true') {
            return response()->json($request->rules()['message'], 404);
        }

        $route = Route::current()->getName();
        // ADADA

        if ($route == "invoice.store") {
            $response  = $this->storeInvoiceServiceClass->store($request->all());
        } else if ($route == "invoice.quick.store") {
            $response  = $this->storeQuickInvoiceServiceClass->store($request->all());
        } else {
            return response()->json([
                "message" => "Not Found Inoive Type"
            ], 404);
        }

        return $response;
    }

    function update(InvoiceRequest $request, $id)
    {
        try {
            if ($request->rules()['message'] != 'true') {
                return response()->json($request->rules()['message'], 404);
            }
            $message =  $this->updateInvoiceServiceClass->update($id);

            if (isset($message)) {
                return $message;
            }

            return response()->json([
                "message" => "success"
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
