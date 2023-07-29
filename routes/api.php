<?php

use App\Http\Controllers\AboutStoreController;
use App\Http\Controllers\AccountStatementController;
use App\Http\Controllers\AddresseController;
use App\Http\Controllers\Admin\setting\AboutController;
use App\Http\Controllers\Admin\setting\AddressTypeController;
use App\Http\Controllers\Admin\setting\AreaController;
use App\Http\Controllers\Admin\setting\BankController;
use App\Http\Controllers\Admin\setting\BusinessCategoryController;
use App\Http\Controllers\Admin\setting\BusinessTypeController;
use App\Http\Controllers\Admin\setting\CityController;
use App\Http\Controllers\Admin\setting\ContactController;
use App\Http\Controllers\Admin\setting\ContactPhoneController;
use App\Http\Controllers\Admin\setting\CountryController;
use App\Http\Controllers\Admin\setting\DepositTermController;
use App\Http\Controllers\Admin\setting\InvoiceExpiryAfterTypeController;
use App\Http\Controllers\Admin\setting\LanguageController;
use App\Http\Controllers\Admin\setting\MessageController;
use App\Http\Controllers\Admin\setting\PaymentMethodController;
use App\Http\Controllers\Admin\setting\RecurringIntervalController;
use App\Http\Controllers\Admin\setting\RoleController;
use App\Http\Controllers\Admin\setting\SendInvoiceOptionController;
use App\Http\Controllers\Admin\setting\SocialMediaController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\CommissionFormController;
use App\Http\Controllers\ConfirmEmailPhoneController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ManagerUserController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\MoneyRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodUserController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductLinkController;
use App\Http\Controllers\ProductStoreController;
use App\Http\Controllers\ProfileBusinessController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\socialMediaProfileController;
use App\Http\Controllers\SupportTypeController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WebhookController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

Route::post('pusher/auth', function (Request $request) {
    if (auth()->check()) {
        $channelData = Broadcast::auth($request);
        return response($channelData);
    }
    return response('Unauthorized', 401);
});

Route::get('/globalData', [SettingController::class, 'settingGuest'])->name('globalData');
Route::get('/databaseSeeder', [SettingController::class, 'databaseSeeder'])->name('databaseSeeder');
Route::get('/delete/data', [SettingController::class, 'deleteData'])->name('deleteData');
Route::post('/artisanOrder', [SettingController::class, 'artisanOrder'])->name('artisanOrder');

Route::post('/register', [ManagerUserController::class, 'register'])->name('register');
Route::post('/confirm-code-phone', [ConfirmEmailPhoneController::class, 'confirmCodePhone']);
Route::post('/confirm-code-email', [ConfirmEmailPhoneController::class, 'confirmCodeEmail']);
Route::post('/send-code-phone', [ConfirmEmailPhoneController::class, 'sendCodePhone']);


Route::group(['middleware' => 'IsEnable'], function () {
    Route::post('/login', [ManagerUserController::class, 'login'])->name('login');
});
Route::post('/forget-password', [ManageUserController::class, 'forgetPassword']);
Route::post('/reset-password', [ManageUserController::class, 'verify']);

Route::get('banks', [BankController::class, 'index'])->name('banks');
Route::get('businessTypes', [BusinessTypeController::class, 'index'])->name('businessTypes');
Route::get('countries', [CountryController::class, 'index'])->name('countries');
Route::get('business_categories', [BusinessCategoryController::class, 'index'])->name('business_categories');
Route::post('otp/verification', [ManagerUserController::class, 'verifyOtp']);




Route::group(['middleware' => 'apiAuth'], function () {

    Route::get('/me', [ManagerUserController::class, 'me'])->name('me');
    Route::post('/logout', [ManagerUserController::class, 'logout'])->name('logout');

    Route::get('cities', [CityController::class, 'cityProfile'])->name('city.profile');
    Route::get('areas', [AreaController::class, 'index'])->name('areas');
    Route::get('address_type', [AddressTypeController::class, 'index'])->name('address_type');
    Route::get('social_media', [SocialMediaController::class, 'index'])->name('social_media');
    Route::get('deposit_term', [DepositTermController::class, 'index'])->name('deposit_term');
    Route::get('languages', [LanguageController::class, 'index'])->name('languages');
    Route::get('recurring_interval', [RecurringIntervalController::class, 'index'])->name('recurring_interval');
    Route::get('send_invoice_options', [SendInvoiceOptionController::class, 'index'])->name('send_invoice_options');
    Route::get('invoice_expiry', [InvoiceExpiryAfterTypeController::class, 'index'])->name('invoice_expiry');
    Route::get('contacts', [ContactController::class, 'index'])->name('contact');
    Route::get('contactphones', [ContactPhoneController::class, 'index'])->name('contactphones');
    Route::post('message/store', [MessageController::class, 'store'])->name('message.store');
    Route::get('abouts', [AboutController::class, 'index'])->name('about');
    Route::get('payment_methods', [PaymentMethodController::class, 'index'])->name('payment_methods');

    Route::post('firebase/addToken', [FirebaseController::class, 'addToken'])->name('firebase.addToken');

    Route::post('changePassword', [ManagerUserController::class, 'changePassword'])->name('change.password');
    Route::get('roles', [RoleController::class, 'index'])->name('roles');


    Route::group(['middleware' => 'access:profile'], function () {
        Route::get('/profile_business', [ProfileBusinessController::class, 'index'])->name('profile_business');
        Route::put('/profile_business/update', [ProfileBusinessController::class, 'update'])->name('profile_business.update');
    });

    Route::group(['middleware' => 'access:customers'], function () {
        Route::get('customers', [CustomerController::class, 'index'])->name('customers');
        Route::get('customer/show/{id}', [CustomerController::class, 'show'])->name('customer.show');
        Route::post('customer/store', [CustomerController::class, 'store'])->name('customer.store');
        Route::put('customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
        Route::delete('customer/delete/{id}', [CustomerController::class, 'delete'])->name('customer.delete');
    });

    Route::group(['middleware' => 'access:products'], function () {
        Route::get('product/categories', [ProductCategoryController::class, 'index'])->name('product.categories');
        Route::get('product/category/show/{id}', [ProductCategoryController::class, 'show'])->name('product.category.show');
        Route::post('product/category/store', [ProductCategoryController::class, 'store'])->name('product.category.store');
        Route::put('product/category/update/{id}', [ProductCategoryController::class, 'update'])->name('product.category.update');
        Route::delete('product/category/delete/{id}', [ProductCategoryController::class, 'delete'])->name('product.category.delete');

        Route::get('products', [ProductController::class, 'index'])->name('products');
        Route::get('product/show/{id}', [ProductController::class, 'show'])->name('product.show');
        Route::post('product/store', [ProductController::class, 'store'])->name('product.store');
        Route::put('product/update/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');
    });


    Route::group(['middleware' => ['Approval', 'access:invoices']], function () {
        Route::group(['middleware' => 'access:show_all_invoices'], function () {
            Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices');
            Route::get('invoice/show/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
        });

        Route::post('invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
        Route::post('invoice/quick/store', [InvoiceController::class, 'store'])->name('invoice.quick.store');
        Route::put('invoice/update/{id}', [InvoiceController::class, 'update'])->name('invoice.update');
    });

    Route::get('social_media_profile', [socialMediaProfileController::class, 'index'])->name('social_media_profile');
    Route::post('social_media_profile/store', [socialMediaProfileController::class, 'store'])->name('social_media_profile.store');
    Route::delete('social_media_profile/delete/{id}', [socialMediaProfileController::class, 'delete'])->name('social_media_profile.delete');

    Route::get('addresses', [AddresseController::class, 'index'])->name('addresses');
    Route::get('addresse/show/{id}', [AddresseController::class, 'show'])->name('addresse.show');
    Route::post('addresse/store', [AddresseController::class, 'store'])->name('addresse.store');
    Route::put('addresse/update/{id}', [AddresseController::class, 'update'])->name('addresse.update');
    Route::delete('addresse/delete/{id}', [AddresseController::class, 'delete'])->name('addresse.delete');


    Route::group(['middleware' => ['Approval', 'access:payment_links']], function () {
        Route::get('payments', [PaymentController::class, 'index'])->name('payments');
        Route::post('payment/store', [PaymentController::class, 'store'])->name('payment.store');
        Route::get('payment/show/{id}', [PaymentController::class, 'show'])->name('payment.show');
        Route::put('payment/update/{id}', [PaymentController::class, 'update'])->name('payment.update');
        Route::delete('payment/delete/{id}', [PaymentController::class, 'delete'])->name('payment.delete');
    });

    Route::group(['middleware' => 'access:users'], function () {

        Route::get('manage_users', [ManageUserController::class, 'index'])->name('ManageUser');
        Route::get('manage_user/show/{id}', [ManageUserController::class, 'show'])->name('ManageUser.show');

        Route::post('manage_user/store', [ManageUserController::class, 'store'])->name('ManageUser.store');
        Route::put('manage_user/update/{id}', [ManageUserController::class, 'update'])->name('ManageUser.update');
    });



    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');


    Route::get('payment_user', [PaymentMethodUserController::class, 'index'])->name('payment_user');
    Route::put('payment_user/update', [PaymentMethodUserController::class, 'update'])->name('payment_user.update');

    Route::get('api_key', [ApiKeyController::class, 'index'])->name('api_key');

    //// in middleware Api Key
    Route::group(['middleware' => 'Approval'], function () {
        Route::get('product_links', [ProductLinkController::class, 'index'])->name('product_links');
        Route::post('product_link/store', [ProductLinkController::class, 'store'])->name('product_link.store');
        Route::get('product_link/show/{id}', [ProductLinkController::class, 'show'])->name('product_link.show');
        Route::put('product_link/update/{id}', [ProductLinkController::class, 'update'])->name('product_link.update');
    });

    Route::get('orders', [OrderController::class, 'index'])->name('orders');
    Route::get('order/show/{id}', [OrderController::class, 'show'])->name('order.show');


    Route::get('commission_forms', [CommissionFormController::class, 'index']);

    Route::get('support_types', [SupportTypeController::class, 'index']);

    Route::get('documents', [DocumentsController::class, 'index']);
    Route::post('documents/store', [DocumentsController::class, 'store']);

    Route::get('refunds', [RefundController::class, 'index']);
    Route::post('refund/store/{invoice_id}', [RefundController::class, 'store']);
    Route::get('refund/show/{id}', [RefundController::class, 'show']);
    Route::get('refund/summury/{invoice_id}', [RefundController::class, 'refund_summury']);
    Route::delete('refund/delete/{id}', [RefundController::class, 'delete']);



    Route::post('request_money/store', [MoneyRequestController::class, 'store']);
    Route::put('request_money/update/{request_money_id}', [MoneyRequestController::class, 'update']);
    Route::get('deposits', [MoneyRequestController::class, 'index']);
    Route::get('request_money/cancel/{id}', [MoneyRequestController::class, 'cancel']);

    Route::get('homePage', [HomePageController::class, 'homePage']);



    Route::post('charge/wallet', [WalletController::class, 'charge_wallet']);

    Route::get('account_statment', [AccountStatementController::class, 'index']);
    Route::post('account_statment/month', [AccountStatementController::class, 'getDataManth']);
    Route::post('multiFactotrAuth/update', [ManagerUserController::class, 'multiFactotrAuth']);

    Route::get('generateSecretKey', [WebhookController::class, 'webhookSecretKey']);
    Route::post('webhook/store', [WebhookController::class, 'Webhook']);

    Route::post('aboutStore/store', [AboutStoreController::class, 'store']);
    Route::get('aboutStore', [AboutStoreController::class, 'index']);
});


Route::group(['middleware' => 'tokenKeyAuth'], function () {
    Route::post('api_key/invoice/store', [InvoiceController::class, 'storeQuick'])->name('api_key.invoice.store');
});


Route::post('store/product_invoice/{product_link_id}', [OrderController::class, 'storeProductInvoice'])->name('order.product_invoice');
Route::post('store/payment_invoice/{payment_link_id}', [OrderController::class, 'storePaymentInvoice'])->name('order.payment_invoice');


Route::get('productsInStore/{title}', [ProductStoreController::class, 'index']);
Route::get('productInStore/{profile_id}/{product_id}', [ProductStoreController::class, 'show']);
Route::post('checkout/invoice/store/{profile_company_name}', [ProductStoreController::class, 'checkoutInvoiceStore']);


// Route::post('transaction/store/', [transactionController::class, 'store']);
// Route::get('transactions', [transactionController::class, 'index']);



Route::post('charge/invoice/{invoice_id}', [UrlController::class, 'chargeInvoice']);
Route::get('PayInvoice/Details/{invoiceId}', [UrlController::class, 'show']);

Route::get('PayPayment/Details/{paymentLinkId}', [PaymentController::class, 'showPaymentDetails']);
Route::get('PayProduct/Details/{productLinkId}', [ProductLinkController::class, 'showProductLinkDetails']);


Route::get('env/data', function () {
    dd(Dotenv\Dotenv::createArrayBacked(base_path())->load());
});


Route::post('ccavanue', function (Request $request) {
    $working_key = '772B64DF7AFF45B384DF75213591A98F';
    $access_code = "AVLQ04KD61BL89QLLB";
    $merchant_data = '';

    foreach ($request->all() as $key => $value) {
        $merchant_data .= $key . '=' . $value . '&';
    }

    function hextobin($hexString)
    {
        $length = strlen($hexString);
        $binString = "";
        $count = 0;
        while ($count < $length) {
            $subString = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);
            if ($count == 0) {
                $binString = $packedString;
            } else {
                $binString .= $packedString;
            }

            $count += 2;
        }
        return $binString;
    }
    function encrypt_2($plainText, $key)
    {
        $key = hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }
    $encrypted_data = encrypt_2($merchant_data, $working_key); // Method for encrypting the data.

    return response()->json([
        'encrypted_data' => $encrypted_data,
        'production_url' => 'https://secure.ccavenue.ae/transaction/transaction.do?command=initiateTransaction&encRequest=' . $encrypted_data . '&access_code=' . $access_code
    ]);

});
