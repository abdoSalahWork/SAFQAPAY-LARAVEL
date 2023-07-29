<?php

use App\Http\Controllers\AboutStoreController;
use App\Http\Controllers\AccountStatementController;
use App\Http\Controllers\Admin\AdminManagerUserController;
use App\Http\Controllers\Admin\AdminProfileBusinessController;
use App\Http\Controllers\Admin\HomePageAdminController;
use App\Http\Controllers\Admin\MailSenderController;
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
use App\Http\Controllers\Admin\setting\SendInvoiceOptionController;
use App\Http\Controllers\Admin\setting\SocialMediaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CommissionPaymentMethodController;
use App\Http\Controllers\MoneyRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentInformationController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\SupportTypeController;
use App\Http\Controllers\WalletAdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Config;

Route::group(['middleware' =>  'superAdminAuth'], function () {
    Route::post('admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::put('admin/update/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('admin/delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');
});
Route::post('pusher/auth', function (Request $request) {
    if (auth()->guard('admin')->check()) {
        Config::set('auth.defaults.guard', 'admin');
        $channelData = Broadcast::auth($request);
        return response($channelData);
    }

    return response('Unauthorized', 401);
});
Route::group(['middleware' => 'adminAuth'], function () {
    Route::get('admins', [AdminController::class, 'index'])->name('admins');
    Route::get('admin/show/{id}', [AdminController::class, 'show'])->name('admin.show');

    Route::get('users', [AdminManagerUserController::class, 'managers'])->name('admin.managers'); // for admin
    Route::put('manager/update/{id}', [AdminManagerUserController::class, 'update'])->name('admin.update.manager'); // for admin

    // Route::get('Profile_business', [AdminManagerUserController::class, 'index'])->name('admin.profile_business'); // for admin
    // Route::put('Profile_business/update/{id}', [AdminManagerUserController::class, 'update'])->name('admin.update.profile_business');

    Route::group(['middleware' => 'access:banks'], function () {
        Route::post('bank/store', [BankController::class, 'store'])->name('bank.store');
        Route::get('bank/show/{id}', [BankController::class, 'show'])->name('bank.show');
        Route::put('bank/update/{id}', [BankController::class, 'update'])->name('bank.update');
        Route::delete('bank/delete/{id}', [BankController::class, 'delete'])->name('bank.delete');
    });

    Route::post('businessType/store', [BusinessTypeController::class, 'store'])->name('businessType.store');
    Route::get('businessType/show/{id}', [BusinessTypeController::class, 'show'])->name('businessType.show');
    Route::put('businessType/update/{id}', [BusinessTypeController::class, 'update'])->name('businessType.update');
    Route::delete('businessType/delete/{id}', [BusinessTypeController::class, 'delete'])->name('businessType.delete');

    Route::get('country/show/{id}', [CountryController::class, 'show'])->name('country.show');
    Route::post('country/store', [CountryController::class, 'store'])->name('country.store');
    Route::put('country/update/{id}', [CountryController::class, 'update'])->name('country.update');
    Route::delete('country/delete/{id}', [CountryController::class, 'delete'])->name('country.delete');

    Route::get('cities', [CityController::class, 'index'])->name('cities');
    Route::post('city/store', [CityController::class, 'store'])->name('city.store');
    Route::get('city/show/{id}', [CityController::class, 'show'])->name('city.show');
    Route::put('city/update/{id}', [CityController::class, 'update'])->name('city.update');
    Route::delete('city/delete/{id}', [CityController::class, 'delete'])->name('city.delete');

    Route::post('area/store', [AreaController::class, 'store'])->name('area.store');
    Route::get('area/show/{id}', [AreaController::class, 'show'])->name('area.show');
    Route::put('area/update/{id}', [AreaController::class, 'update'])->name('area.update');
    Route::delete('area/delete/{id}', [AreaController::class, 'delete'])->name('area.delete');

    Route::post('address_type/store', [AddressTypeController::class, 'store'])->name('address_type.store');
    Route::get('address_type/show/{id}', [AddressTypeController::class, 'show'])->name('address_type.show');
    Route::put('address_type/update/{id}', [AddressTypeController::class, 'update'])->name('address_type.update');
    Route::delete('address_type/delete/{id}', [AddressTypeController::class, 'delete'])->name('address_type.delete');

    Route::post('social_media/store', [SocialMediaController::class, 'store'])->name('social_media.store');
    Route::get('social_media/show/{id}', [SocialMediaController::class, 'show'])->name('social_media.show');
    Route::put('social_media/update/{id}', [SocialMediaController::class, 'update'])->name('social_media.update');
    Route::delete('social_media/delete/{id}', [SocialMediaController::class, 'delete'])->name('social_media.delete');

    Route::post('deposit_term/store', [DepositTermController::class, 'store'])->name('deposit_term.store');
    Route::get('deposit_term/show/{id}', [DepositTermController::class, 'show'])->name('deposit_term.show');
    Route::put('deposit_term/update/{id}', [DepositTermController::class, 'update'])->name('deposit_term.update');
    Route::delete('deposit_term/delete/{id}', [DepositTermController::class, 'delete'])->name('deposit_term.delete');

    Route::post('language/store', [LanguageController::class, 'store'])->name('language.store');
    Route::get('language/show/{id}', [LanguageController::class, 'show'])->name('language.show');
    Route::put('language/update/{id}', [LanguageController::class, 'update'])->name('language.update');
    Route::delete('language/delete/{id}', [LanguageController::class, 'delete'])->name('language.delete');

    Route::post('recurring_interval/store', [RecurringIntervalController::class, 'store'])->name('recurring_interval.store');
    Route::get('recurring_interval/show/{id}', [RecurringIntervalController::class, 'show'])->name('recurring_interval.show');
    Route::put('recurring_interval/update/{id}', [RecurringIntervalController::class, 'update'])->name('recurring_interval.update');
    Route::delete('recurring_interval/delete/{id}', [RecurringIntervalController::class, 'delete'])->name('recurring_interval.delete');

    Route::post('send_invoice_option/store', [SendInvoiceOptionController::class, 'store'])->name('send_invoice_option.store');
    Route::get('send_invoice_option/show/{id}', [SendInvoiceOptionController::class, 'show'])->name('send_invoice_option.show');
    Route::put('send_invoice_option/update/{id}', [SendInvoiceOptionController::class, 'update'])->name('send_invoice_option.update');
    Route::delete('send_invoice_option/delete/{id}', [SendInvoiceOptionController::class, 'delete'])->name('send_invoice_option.delete');


    Route::post('business_category/store', [BusinessCategoryController::class, 'store'])->name('business_category.store');
    Route::get('business_category/show/{id}', [BusinessCategoryController::class, 'show'])->name('business_category.show');
    Route::put('business_category/update/{id}', [BusinessCategoryController::class, 'update'])->name('business_category.update');
    Route::delete('business_category/delete/{id}', [BusinessCategoryController::class, 'delete'])->name('business_category.delete');

    Route::get('invoice_expiry', [InvoiceExpiryAfterTypeController::class, 'indexAdmin'])->name('invoice_expiry.store');
    Route::get('invoice_expiry/show/{id}', [InvoiceExpiryAfterTypeController::class, 'show'])->name('invoice_expiry.show');
    Route::put('invoice_expiry/update/{id}', [InvoiceExpiryAfterTypeController::class, 'update'])->name('invoice_expiry.update');

    Route::put('contact/update', [ContactController::class, 'update'])->name('contact.update');

    Route::get('contactphones/show/{id}', [ContactPhoneController::class, 'show'])->name('contactphones.show');
    Route::post('contactphones/store', [ContactPhoneController::class, 'store'])->name('contactphones.store');
    Route::put('contactphones/update/{id}', [ContactPhoneController::class, 'update'])->name('contactphones.update');
    Route::delete('contactphones/delete/{id}', [ContactPhoneController::class, 'delete'])->name('contactphones.delete');

    // Route::prefix('admin')
    Route::get('messages', [MessageController::class, 'index'])->name('messages');
    Route::get('message/show/{id}', [MessageController::class, 'show'])->name('message.show');
    Route::delete('message/delete/{id}', [MessageController::class, 'delete'])->name('message.delete');
    // Route::prefix('admin')
    Route::get('about/show/{id}', [AboutController::class, 'show'])->name('about.show');
    Route::post('about/store', [AboutController::class, 'store'])->name('about.store');
    Route::put('about/update/{id}', [AboutController::class, 'update'])->name('about.update');
    Route::delete('about/delete/{id}', [AboutController::class, 'delete'])->name('about.delete');

    Route::post('payment_method/store', [PaymentMethodController::class, 'store'])->name('payment_method.store');
    Route::get('payment_method/show/{id}', [PaymentMethodController::class, 'show'])->name('payment_method.show');
    Route::put('payment_method/update/{id}', [PaymentMethodController::class, 'update'])->name('payment_method.update');
    Route::delete('payment_method/delete/{id}', [PaymentMethodController::class, 'delete'])->name('payment_method.delete');

    Route::get('mail_sender', [MailSenderController::class, 'index']); // for admin
    Route::put('mail_sender', [MailSenderController::class, 'update']); // for admin

    Route::post('support_type/store', [SupportTypeController::class, 'store']);
    Route::get('support_type/show/{id}', [SupportTypeController::class, 'show']);
    Route::put('support_type/update/{id}', [SupportTypeController::class, 'update']);
    Route::delete('support_type/delete/{id}', [SupportTypeController::class, 'delete']);

    Route::get('admin_profiles', [AdminProfileBusinessController::class, 'index']); // for admin
    Route::get('admin_profile/show/{id}', [AdminProfileBusinessController::class, 'show']); // for admin
    Route::put('admin_profile/update/{id}', [AdminProfileBusinessController::class, 'update']); // for admin

    Route::put('request_money/confirm/{profile_id}', [MoneyRequestController::class, 'confirm_admin']);
    Route::get('money_requests', [MoneyRequestController::class, 'index_admin']);
    Route::delete('request_money/delete/{id}', [MoneyRequestController::class, 'delete_admin']);


    Route::get('confirm_refund/{invoice_id}', [RefundController::class, 'confirm_refund']);
    Route::get('refunds', [RefundController::class, 'index_admin']);
    // Route::get('refund/show/{id}', [RefundController::class, 'show']);
    Route::delete('refund/delete/{id}', [RefundController::class, 'delete_admin']);


    Route::get('payment_method/commssion', [CommissionPaymentMethodController::class, 'index']);
    Route::post('payment_method/commssion/store', [CommissionPaymentMethodController::class, 'store']);
    Route::get('payment_method/commssion/show/{id}', [CommissionPaymentMethodController::class, 'show']);
    Route::delete('payment_method/commssion/delete/{id}', [CommissionPaymentMethodController::class, 'delete']);

    Route::get('wallet', [WalletAdminController::class, 'index']);

    Route::get('homePage', [HomePageAdminController::class, 'homePage']);

    Route::get('account_statment', [AccountStatementController::class, 'index_admin']);

    Route::get('aboutStore', [AboutStoreController::class, 'index_admin']);
    Route::put('aboutStore/update/{id}', [AboutStoreController::class, 'update_admin']);


    Route::get('notifications', [NotificationController::class, 'index_admin']);

    Route::get('commission', [CommissionController::class, 'show']);
    Route::put('commission/update', [CommissionController::class, 'update']);

    Route::get('paymentInformation', [PaymentInformationController::class, 'show']);
    Route::put('paymentInformation/update', [PaymentInformationController::class, 'update']);



});
