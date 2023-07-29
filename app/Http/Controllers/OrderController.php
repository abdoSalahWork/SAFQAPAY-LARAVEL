<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentInvoiceRequest;
use App\Http\Requests\ProductLinkInvoiceRequest;
use App\Models\Invoice\Invoice;
use App\Models\Order;
use App\Services\Invoice\StoreInvicePaymentService;
use App\Services\Invoice\StoreInviceProductService;
use App\Services\NotificationService;
use App\Services\SendMailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class OrderController extends Controller
{
    private  $storeInviceProductServiceClass, $storeInvicePaymentServiceClass, $notificationServiceClass, $sendMailService;

    public function __construct(
        StoreInviceProductService $storeInviceProductService,
        StoreInvicePaymentService $storeInvicePaymentService,
        NotificationService $notificationService,
        SendMailService $sendMailService

    ) {
        $this->storeInviceProductServiceClass = $storeInviceProductService;
        $this->storeInvicePaymentServiceClass = $storeInvicePaymentService;
        $this->notificationServiceClass = $notificationService;
        $this->sendMailService = $sendMailService;

    }

    public function index()
    {
        $user = auth()->user();

        $paid_orders = Invoice::where('invoice_type', '!=', 'invoice')
            ->where('status', 'paid')
            ->where('profile_business_id', $user->profile_business_id)->get();

        return response()->json(['data' => $paid_orders]);
    }

    public function  storeProductInvoice(ProductLinkInvoiceRequest $request, $order_id)
    {
        if (isset($request->rules()['message'])) {
            return response()->json($request->rules()['message'], 404);
        }

        $invoice = $this->storeInviceProductServiceClass->store($request, $order_id);
        if ($invoice) {
            $order = Order::create([
                'invoice_id' => $invoice->id,
                'order_status' => 'pending',
            ]);
          
            $this->notification($order->id, $invoice->vendor->full_name, $invoice);
            $this->sendMailService->send_email_order($order->id ,$invoice);

            DB::commit();
            return response()->json([
                'invoice' => $invoice->id,
                "message" => "Success"
            ]);
        }

        return response()->json([
            "message" => "this order is not found"
        ], 404);
    }

    public function storePaymentInvoice(PaymentInvoiceRequest $request, $order_id)
    {
        if (isset($request->rules()['message'])) {
            return response()->json($request->rules()['message'], 404);
        }

        $invoice = $this->storeInvicePaymentServiceClass->store($request, $order_id);

        if ($invoice) {
            $order = Order::create([
                'invoice_id' => $invoice->id,
                'order_status' => 'pending',
            ]);
            $this->notification($order->id ,$invoice->vendor->full_name ,$invoice);

            DB::commit();
            return response()->json([
                'invoice' => $invoice->id,
                "message" => "Success"
            ]);
        }

        return response()->json([
            "message" => "this order is not found"
        ], 404);
    }

    public function show($id)
    {
        $user = auth()->user();

        $order = Invoice::where('invoice_type', '!=', 'invoice')
            ->where('status', 'paid')
            ->where('profile_business_id', $user->profile_business_id)->with('invoice_item')->with('transcation')->find($id);
        if ($order) {
            return response()->json(['data' => $order]);
        } else
            return response()->json(['message' => 'order not found'], 404);
    }

    public function notification($order_id,$creator_name, $invoice)
    {

        $domain = URL::to('/');
        $api = "$domain/api/order/show/$invoice->id";
        $text = "New Order " . $order_id . " Recieved, click...";
        $column = 'notification_new_order';

        $this->notificationServiceClass->notification($order_id, $invoice->profile_business_id, $text, $api, $column , $creator_name);
    }
}
