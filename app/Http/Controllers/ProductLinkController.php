<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductLink;
use App\Models\ProductLinkCategryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductLinkController extends Controller
{
    private $test = [];

    public function index()
    {
        $user = auth()->user();

        $productsLinks = ProductLink::where('profile_id', $user->profile_business_id)->get();
        return response()->json(['data' => $productsLinks]);
    }

    public function show($id)
    {
        $user = auth()->user();


        $product_link = ProductLink::with('Products.category')->with(['transaction', 'view'])->where('profile_id', $user->profile_business_id)->find($id);

        return $product_link ? response()->json(['data' => $product_link]) :
            response()->json(['message' => 'product link not found'], 404);
    }

    public function showProductLinkDetails($product_id)
    {
        // Use Grope By Category In Product

        $products = ProductLink::with(['profile' => function ($q) {
            $q->with(['aboutStore' => function ($q) {
                $q->where('is_active', true);
            }]);
            $q->selected();
        }])->with(['Products' => function ($q) {
            $q->with('category');
            $q->with('currency');

            $q->select('product_id', 'category_id', 'name_en', 'name_ar', 'quantity', 'price', 'product_image', 'currency_id');
        }])->select('id', 'profile_id', 'created_at')->findOrFail($product_id);

        $urlLogoStore = url("image/aboutStore/");
        return response()->json(['data' => $products, 'urlLogoStore' => $urlLogoStore]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'is_active' => 'boolean',
            'Terms_and_conditions' => 'nullable|string',
            'products' => 'array|required',
            'products.*' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        $req = $request->all();
        $req['is_active'] = $request->is_active ? true : false;

        unset($req['products']);
        $req += ["profile_id" => $user->profile_business_id];
        $req += ["user_id" => $user->id];
        if ($request->products) {

            $products = Product::whereIn('id', $request->products)
                ->stockable()->active()->get();

            if ($products->count()) {

                $product_link = ProductLink::create($req);
                foreach ($products as $product) {
                    ProductLinkCategryProduct::create([
                        'product_link_id' => $product_link->id,
                        'product_id' => $product->id,
                    ]);
                }
                return response()->json(['message' => 'success']);
            }
            return response()->json(['message' => 'this product is not found'], 404);
        }
        return response()->json(['message' => 'Faild'], 404);
    }


    public function update(Request $request, $id)
    {
        $product_link = ProductLink::find($id);
        if ($product_link) {
            $user = auth()->user();
            $validator = Validator::make($request->all(), [
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'is_active' => 'boolean',
                'Terms_and_conditions' => 'nullable|string',
                'products' => 'array|required',
                'products.*' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 404);
            }

            $req = $request->all();
            unset($req['products']);
            $req += ["profile_id" => $user->profile_business_id];
            $req += ["user_id" => $user->id];

            $products = Product::whereIn('id', $request->products)
                ->stockable()->active()->get();
            if ($products->count()) {
                $product_link->update($req);
                ProductLinkCategryProduct::where('product_link_id', $product_link->id)->delete();
                foreach ($products as $product) {
                    ProductLinkCategryProduct::create([
                        'product_link_id' => $product_link->id,
                        'product_id' => $product->id,
                    ]);
                }
                return response()->json(['message' => 'success']);
            } else
                return response()->json(['message' => 'product is required'], 404);
        }
        return response()->json(['message' => 'product link is not found'], 404);
    }
}
