<?php

use App\Http\Controllers\SettingController;
use App\Models\Invoice\SendInvoiceOption;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\setting\Bank;
use App\Models\setting\RecurringInterval;
use Illuminate\Support\Facades\Route;
use App\Models\setting\BusinessType;
use App\Models\setting\Category;
use App\Models\setting\Country;
use App\Models\setting\DepositTerm;
use App\Models\setting\InvoiceExpiryAfterType;
use App\Models\setting\Language;
use App\Models\setting\SocialMedia;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('web.welcome');

Route::get('/login', function () {

    return view('login');
})->name('web.login');

Route::get('/logout', function () {
    return view('logout');
})->name('web.logout');

Route::get('/me', function () {
    return view('me');
})->name('web.me');


Route::get('/register', function () {
    $businessType = BusinessType::get();
    $category = Category::get();
    $country = Country::get();
    $depositTerm = DepositTerm::where(['default' => true])->get(); // not access user
    $invoiceExpiryAfterType = InvoiceExpiryAfterType::where(['default' => true])->get(); // not access user
    $language = Language::get();
    $socialMedia = SocialMedia::get();

    return view(
        'register',
        compact('businessType', 'category', 'country', 'depositTerm', 'invoiceExpiryAfterType', 'language', 'socialMedia')
    );
})->name('web.register');




Route::get('/managers', function () {
    return view('users/managers');
})->name('web.managers');

Route::get('/admin/managers', function () {
    return view('users/users');
})->name('web.admin.managers');



Route::get('manager/update/{id}', function ($id) {

    // return $id ;
    $businessType = BusinessType::get();
    $category = Category::get();
    $country = Country::get();
    $depositTerm = DepositTerm::where(['default' => true])->get(); // not access user
    $invoiceExpiryAfterType = InvoiceExpiryAfterType::where(['default' => true])->get(); // not access user
    $language = Language::get();
    $socialMedia = SocialMedia::get();

    $user = User::find($id);
    $user->avatarUrl = route('avatar.user', [$id, $user->avatar]);
    return view(
        'users/updateManager',
        compact('user', 'businessType', 'category', 'country', 'depositTerm', 'invoiceExpiryAfterType', 'language', 'socialMedia')
    );
})->name('web.update.manager');

Route::get('admin/manager/update/{id}', function ($id) {
    // return $id ;

    $businessType = BusinessType::get();
    $category = Category::get();
    $country = Country::get();
    $depositTerm = DepositTerm::where(['default' => true])->get(); // not access user
    $invoiceExpiryAfterType = InvoiceExpiryAfterType::where(['default' => true])->get(); // not access user
    $language = Language::get();
    $socialMedia = SocialMedia::get();

    $user = User::find($id);

    $user->avatarUrl = route('avatar.user', [$id, $user->avatar]);


    return view(
        'users/adminUpdateManager',
        compact('user', 'businessType', 'category', 'country', 'depositTerm', 'invoiceExpiryAfterType', 'language', 'socialMedia')
    );
})->name('web.admin.update.manager');



Route::get('changePassword', function () {
    return view('users/changePassword');
})->name('web.change.password');

Route::get('invoices/create', function () {
    $send_invoice_option = SendInvoiceOption::get();
    $country = Country::get();
    $language = Language::get();
    $recurring_nterval = RecurringInterval::get();

    return view('invoices.create', compact(['send_invoice_option', 'country', 'language', 'recurring_nterval']));
})->name('web.invoice.create');

Route::get('customers', function () {

    return view('customers.viewCustomer');
})->name('web.customers');

Route::get('customers/create', function () {
    $countries = Country::get();
    $banks = Bank::get();
    return view('customers.addCustomer', compact(['countries', 'banks']));
})->name('web.customer.create');

Route::post('customers/edit', function (Request $request) {
    $id = $request->id;
    $countries = Country::get();
    $banks = Bank::get();
    return view('customers.updateCustomer', compact(['countries', 'banks', 'id']));
})->name('web.customer.edit');

Route::get('product/categories', function () {
    return view('Product.viewProductCategory');
})->name('web.product.categories');

Route::get('product/categories/create', function () {
    return view('Product.addProductCategory');
})->name('web.product.category.create');

Route::post('product/category/edit', function (Request $request) {
    $id = $request->id;
    return view('Product.updateProductCategory', compact('id'));
})->name('web.product.category.edit');

Route::get('products', function () {
    $products = Product::get();
    return view('Product.viewProduct', compact('products'));
})->name('web.product');

Route::get('product/add', function () {
    $productCategories = ProductCategory::get();
    return view('Product.addProduct', compact('productCategories'));
})->name('web.product.add');


Route::post('product/edit', function (Request $request) {
    $productCategories = ProductCategory::get();
    $product = Product::find($request->id);
    $product->product_image = route('image.product', [$product->manager_user_id]) . '/' . $product->product_image;
    return view('Product.updateProduct', compact(['productCategories', 'product']));
})->name('web.product.edit');




Route::get('/social_media', function () {
    return view('admin.social media.viewSocialMedia');
})->name('web.social_media');

Route::get('/social_media/add', function () {
    return view('admin.social media.addSocialMedia');
})->name('web.social_media.add');

Route::post('/social_media/edite', function (Request $request) {
    $id = $request->id;
    return view('admin.social media.updateSocialMedia', compact('id'));
})->name('web.social_media.edite');

Route::get('/bank', function () {
    return view('admin.bank.viewBank');
})->name('web.bank');

Route::get('/bank/add', function () {
    $countries = Country::get();
    return view('admin.bank.addBank', compact(['countries']));
})->name('web.bank.add');

Route::post('/bank/edite', function (Request $request) {
    $id = $request->id;
    $countries = Country::get();
    return view('admin.bank.updateBank', compact(['countries', 'id']));
})->name('web.bank.edite');


Route::get('/business_type', function () {
    return view('admin.business type.view');
})->name('web.businessTypes');

Route::get('/business_type/add', function () {
    return view('admin.business type.add',);
})->name('web.businessType.add');

Route::post('/business_type/edit', function (Request $request) {
    $businessType = BusinessType::find($request->id);
    $businessType->business_logo = route('logo.businessType', [$businessType->business_logo]);
    return view('admin.business type.update', compact('businessType'));
})->name('web.businessType.edit');

Route::get('/countries', function () {
    return view('admin.country.view');
})->name('web.countries');

Route::get('/country/add', function () {
    return view('admin.country.add',);
})->name('web.country.add');

Route::post('/country/edit', function (Request $request) {
    $country = Country::find($request->id);
    $country->flag = route('flag.country', [$country->flag]);
    return view('admin.country.update', compact('country'));
})->name('web.country.edit');

Route::get('/deposit_term', function () {
    return view('admin.deposit_term.viewDepositTerm');
})->name('web.deposit_term');

Route::get('/deposit_term/add', function () {
    return view('admin.deposit_term.addDepositTerm');
})->name('web.deposit_term.add');

Route::post('/deposit_term/edit', function (Request $request) {
    $id = $request->id;
    return view('admin.deposit_term.updateDepositTerm', compact('id'));
})->name('web.deposit_term.edit');

Route::get('/languages', function () {
    return view('admin.language.viewLanguages');
})->name('web.languages');

Route::get('/language/add', function () {
    return view('admin.language.addLanguage',);
})->name('web.language.add');

Route::post('/language/edit', function (Request $request) {
    $id = $request->id;
    return view('admin.language.updateLanguage', compact('id'));
})->name('web.language.edit');

Route::get('/recurring_interval', function () {
    return view('admin.recurring interval.viewRecurringInterval');
})->name('web.recurring_interval');


Route::get('/recurring_interval/add', function () {
    return view('admin.recurring interval.addRecurringInterval',);
})->name('web.recurring_interval.add');

Route::post('/recurring_interval/edit', function (Request $request) {
    $id = $request->id;
    return view('admin.recurring interval.updateRecurringInterval', compact('id'));
})->name('web.recurring_interval.edit');


Route::get('/send_invoice_options', function () {
    return view('admin.send invoice option.viewSendInvoiceOption');
})->name('web.send_invoice_options');


Route::get('/send_invoice_option/add', function () {
    return view('admin.send invoice option.addSendInvoiceOption',);
})->name('web.send_invoice_option.add');


Route::post('/send_invoice_option/edit', function (Request $request) {
    $id = $request->id;
    return view('admin.send invoice option.updateSendInvoiceOption', compact('id'));
})->name('web.send_invoice_option.edit');


///  business_categories

Route::get('/business_categories', function () {
    return view('admin.business category.viewBusinessCategory');
})->name('web.business_categories');

Route::get('/business_category/add', function () {
    return view('admin.business category.addBusinessCategory');
})->name('web.business_category.add');

Route::post('/business_category/edit', function (Request $request) {
    $id = $request->id;
    return view('admin.business category.updateBusinessCategory', compact('id'));
})->name('web.business_category.edit');

///  business_categories

Route::get('/invoice_expiry', function () {
    return view('admin.invoice expiry.viewInvoiceExpiry');
})->name('web.invoice_expiry');

Route::get('/invoice_expiry/add', function () {
    return view('admin.invoice expiry.addInvoiceExpiry');
})->name('web.invoice_expiry.add');

Route::post('/invoice_expiry/edit', function (Request $request) {
    $id = $request->id;
    return view('admin.invoice expiry.updateInvoiceExpiry', compact('id'));
})->name('web.invoice_expiry.edit');

