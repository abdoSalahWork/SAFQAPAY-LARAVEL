<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Models\AboutStore;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProfileBusiness;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductStoreController extends Controller
{
    public function index($title)
    {
        // $categories = ProductCategory::where('profile_business_id', $profile_id)->get();
        // // Use Grope By Category In Product
        // $products = Product::where('profile_business_id', $profile_id)->where('in_store', true)->get();
        $myStore = AboutStore::where('title', $title)->where('is_active', true)->with(['products', 'productCategories'])->first();
        if ($myStore) {
            $profile = ProfileBusiness::findOrFail($myStore->profile_id);

            $urlImage = url("image/product/$myStore->profile_id");
            return  response()->json(['myStore' => $myStore, 'urlImage' => $urlImage, "profile" => $profile]);
        }
        else{
            return  response()->json(['message' =>'you can not access this store contact with support']);

        }

        // return  response()->json(['products' => $products, 'categories' => $categories, 'urlImage' => $urlImage, "profile" => $profile]);
    }

    public function show($profile_id, $product_id)
    {
        $product = Product::where('profile_business_id', $profile_id)->where('in_store', true)->with('category')->find($product_id);

        $product->urlImage = url("image/product/$profile_id");
        return $product ?  response()->json(['data' => $product]) :
            response()->json(['message' => 'product is not found'], 404);
    }

    function checkoutInvoiceStore(ProductStoreRequest $request, $profile_company_name)
    {

        $profile = ProfileBusiness::where('company_name',$profile_company_name)->select('id', 'language_id', 'country_id')->firstOrFail();

        if (isset($request->rules()['message'])) {
            return response()->json($request->rules()['message'], 404);
        }

        $data = $request->all();
        unset($data['products']);


        $expiry_date = Carbon::parse()->addDays(3);

        $data['expiry_date'] = $expiry_date;

        $data['profile_business_id'] = $profile->id;
        $data['invoice_type'] = 'invoice';
        $data['language_id'] = $profile->language_id;
        $data['currency_id'] = $profile->country_id;
        $data['send_invoice_option_id'] = 0;

        $data['invoice_display_value']  = 0;

        $products = [];
        $products_quantity = [];
        foreach ($request->products as $prductItem) {

            $product = Product::where('id', $prductItem['id'])->where('profile_business_id', $profile->id)
                ->select('id', 'name_en', 'name_ar', 'price', 'quantity')->firstOrFail();

            if ($product->quantity >= $prductItem['quantity']) {
                $products[] = $product;
                $products_quantity[] = $prductItem['quantity'];
                $data['invoice_display_value'] += $prductItem['quantity'] * $product->price;
            } else {
                return response()->json(['message' => 'this quantity is greater'], 404);
            }
        }
        $data['invoice_value'] = $data['invoice_display_value'];

        DB::beginTransaction();

        $createInvoice = Invoice::create($data);

        if ($createInvoice) {
            foreach ($products as $index => $productItem) {
                $productItem->update([
                    'quantity' => $productItem->quantity - $products_quantity[$index]
                ]);
                InvoiceItem::create([
                    'invoice_id' => $createInvoice->id,
                    'product_name' => $productItem->name_en,
                    'product_quantity' => $products_quantity[$index],
                    'product_price' => $productItem->price,
                ]);
            }
            DB::commit();
        }
        return response()->json([
            'invoice' => $createInvoice->id,
            'message' => 'success'
        ]);
    }
}
