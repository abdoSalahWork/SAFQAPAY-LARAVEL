<?php

namespace App\Http\Controllers;

use App\Models\Invoice\Invoice;
use App\Models\setting\BusinessType;
use App\Models\setting\Category;
use App\Models\setting\Country;
use App\Models\setting\DepositTerm;
use App\Models\setting\InvoiceExpiryAfterType;
use App\Models\setting\Language;
use App\Models\setting\SocialMedia;
use App\Models\setting\Bank;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\InstallSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function settingGuest()
    {
        $businessType = BusinessType::get();
        $category = Category::get();
        $country = Country::get();
        $depositTerm = DepositTerm::get(); // not access user
        $invoiceExpiryAfterType = InvoiceExpiryAfterType::get(); // not access user
        $language = Language::get();
        $socialMedia = SocialMedia::get();
        $banks = Bank::get();
        return response()->json([
            'business_type' => $businessType,
            'category' => $category,
            'country' => $country,
            'deposit_term' => $depositTerm,
            'invoice_expiry_after_type' => $invoiceExpiryAfterType,
            'language' => $language,
            'social_media' => $socialMedia,
            'banks' => $banks,
        ]);
    }
    public function deleteData()
    {
        return response()->json(['invoices' => Invoice::get()]);
    }

    public function artisanOrder(Request $request)
    {
        $status = Artisan::call($request->order);
        return response()->json([$request['order'] => 'success', 'status' => $status]);
    }
}
