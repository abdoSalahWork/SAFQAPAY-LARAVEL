<?php

namespace App\Services\Invoice;

use App\Models\Invoice\Invoice;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice\InvoiceItem;
use App\Models\Product;
use App\Models\ProductLink;
use App\Models\ProductLinkCategryProduct;
use App\Models\ProductLinkInvoice;
use App\Models\ProfileBusiness;
use App\Models\setting\Country;
use App\Services\SendMailService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StoreInviceProductService
{

    function store($request, $productLinkId)
    {

        $data = $request->all();
        unset($data['prductItems']);

        $productLink = ProductLink::findOrFail($productLinkId);
        $expiry_date = Carbon::parse()->addDays(3);
        $data['expiry_date'] = $expiry_date;
        $data['manager_user_id'] = $productLink->user_id;
        $data['profile_business_id'] = $productLink->profile_id;
        $data['invoice_type'] = 'product_invoice';
        $data['invoice_display_value']  = 0;
        $data['currency_id'] = $productLink->profile->country_id;

        $products = [];

        foreach ($request->prductItems as $prductItem) {

            $product = ProductLinkCategryProduct::where('product_link_id', $productLinkId)->where('product_id', $prductItem['product_id'])
                ->with(['products' => function ($q) use ($prductItem) {
                    $q->stockable();
                    $q->length($prductItem['product_quantity']);
                }])->first();

            if ($product) {
                $products[] = $product;
                if (!$product->products) {
                    return;
                }

                $data['invoice_display_value'] += $prductItem['product_quantity'] * $product->products->price;

                $validateInvoceItem = Validator::make($prductItem, [
                    "product_quantity"  => 'required|integer|min:1',
                    "product_id"  => 'required|integer|min:1|exists:products,id',
                ]);

                if ($validateInvoceItem->fails()) {
                    return response()->json($validateInvoceItem->errors(), 404);
                }
            }
        }
        $data['invoice_value'] = $data['invoice_display_value'];

        DB::beginTransaction();

        $createInvoice = Invoice::create($data);

        ProductLinkInvoice::create([
            'product_link_id' => $productLink->id,
            'invoice_id' => $createInvoice->id,
        ]);

        if ($createInvoice) {
            foreach ($products as  $productLinkCategry) {
                InvoiceItem::create([
                    'invoice_id' => $createInvoice->id,
                    'product_name' => $productLinkCategry->products->name_en,
                    'product_quantity' => $prductItem['product_quantity'],
                    'product_price' => $productLinkCategry->products->price,
                ]);
            }
        }
        return $createInvoice;
    }
}
