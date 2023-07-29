<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;


Route::get('businessType/{name}', [ImageController::class, 'logoBsusinessType'])->name('logo.businessType');
Route::get('product/{id}/{name}', [ImageController::class, 'imageProduct'])->name('image.product');
Route::get('country/{name}', [ImageController::class, 'flagCountry'])->name('flag.country');
Route::get('user/{id}/{name}', [ImageController::class, 'avatarUser'])->name('avatar.user');
Route::get('profile/{id}/{name}', [ImageController::class, 'logoProfile'])->name('image.profile');
Route::get('payment_method/{name}', [ImageController::class, 'logoPaymentMethod'])->name('logo.payment_method');

Route::get('invoice/{id}/{name}', [ImageController::class, 'fileInvice'])->name('file.invoice');
Route::get('message/{name}', [ImageController::class, 'imageMessage'])->name('image.message');
Route::get('documents/{name}', [ImageController::class, 'imageDocuments'])->name('image.documents');
Route::get('socialMedia/{name}', [ImageController::class, 'iconSocialMedia'])->name('image.SocialMedia');
Route::get('aboutStore/{name}', [ImageController::class, 'logoAboutStore']);
