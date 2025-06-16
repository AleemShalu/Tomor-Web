<?php

use App\Http\Controllers\ValidationController;
use Illuminate\Support\Facades\Route;

//user
Route::group(['prefix' => 'validation'], function () {
    Route::post('/validate-contact-no', [ValidationController::class, 'validateContactNo'])->name('validate-contact-no');
    Route::post('/validate-email', [ValidationController::class, 'validateEmail'])->name('validate-email');
    Route::post('/validate-usher-code', [ValidationController::class, 'validateUsherCode'])->name('validate-usher-code');
    Route::post('/validate-tax-id', [ValidationController::class, 'validateTaxId'])->name('validation.validate-tax-id');
    Route::post('/validate-commercial-registration', [ValidationController::class, 'validateCommercialRegistration'])->name('validation.validate-commercial-registration');
    Route::post('/validate-municipal-license', [ValidationController::class, 'validateMunicipalLicense'])->name('validation.validate-municipal-license');
    Route::post('/validate-iban', [ValidationController::class, 'validateIban'])->name('validation.validate-iban');
    Route::post('/validate-contact-no-store', [ValidationController::class, 'validateContactNoStore'])->name('validation.validate-contact-no-store');
    Route::post('/validate-contact-no-store-secondary', [ValidationController::class, 'validateContactNoStoreSecondary'])->name('validation.validate-contact-no-store-secondary');
});
