<?php

use App\Http\Controllers\ApiAuthenticationController;
use App\Http\Controllers\ApiComplaintController;
use App\Http\Controllers\ApiCoverLetterController;
use App\Http\Controllers\ApiDonationBillController;
use App\Http\Controllers\ApiDonationController;
use App\Http\Controllers\ApiFamilyCardController;
use App\Http\Controllers\ApiFamilyMemberController;
use App\Http\Controllers\ApiHouseController;
use App\Http\Controllers\ApiImportantNumberController;
use App\Http\Controllers\ApiNoticeController;
use App\Http\Controllers\ApiPanicButtonController;
use App\Http\Controllers\ApiPollingController;
use App\Http\Controllers\ApiProfileController;
use App\Http\Controllers\ApiUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1', 'middleware' => 'api'], function () {
    Route::post('check-family-card', [ApiAuthenticationController::class, 'CheckFamilyCard']);
    Route::post('login-warga', [ApiAuthenticationController::class, 'loginWarga'])->name('login');
    Route::post('register', [ApiAuthenticationController::class, 'register']);
    Route::post('confirm-phone-number', [ApiAuthenticationController::class, 'confirmPhoneNumber']);
    Route::post('change-password', [ApiAuthenticationController::class, 'changePassword']);
    // Route::post('confirm-otp-reset-password', [ApiAuthenticationController::class, 'confirmOtpResetPassword']);
    
    Route::middleware('jwt.auth')->group(function () {
        Route::post('confirm-otp', [ApiAuthenticationController::class, 'confirmVerificationCode']);
        Route::post('reconfirm-otp', [ApiAuthenticationController::class, 'resendCodeVerification']);
        Route::post('logout', [ApiAuthenticationController::class, 'logout']);
        // users
        Route::get('users', [ApiUserController::class, 'index']);
        Route::post('users', [ApiUserController::class, 'store']);
        Route::get('users/{user}/edit', [ApiUserController::class, 'edit']);
        Route::put('users/{user}', [ApiUserController::class, 'update']);
        Route::delete('users', [ApiUserController::class, 'destroy']);
        // houses
        Route::get('houses', [ApiHouseController::class, 'index']);
        Route::post('houses', [ApiHouseController::class, 'store']);
        Route::get('houses/{house}/edit', [ApiHouseController::class, 'edit']);
        Route::put('houses/{house}', [ApiHouseController::class, 'update']);
        // kk
        Route::get('family-cards', [ApiFamilyCardController::class, 'index']);
        Route::post('family-cards', [ApiFamilyCardController::class, 'store']);
        Route::get('family-cards/{family_card}/edit', [ApiFamilyCardController::class, 'edit']);
        Route::put('family-cards/{family_card}', [ApiFamilyCardController::class, 'update']);
        Route::delete('family-cards/{family_card}', [ApiFamilyCardController::class, 'destroy']);
        // family member
        Route::get('family-members', [ApiFamilyMemberController::class, 'index']);
        Route::post('family-members', [ApiFamilyMemberController::class, 'store']);
        Route::get('family-members/{family_member}/edit', [ApiFamilyMemberController::class, 'edit']);
        Route::put('family-members/{family_member}', [ApiFamilyMemberController::class, 'update']);
        Route::delete('family-members/{family_member}', [ApiFamilyMemberController::class, 'destroy']);

        // profile
        Route::get('profile', [ApiProfileController::class, 'getProfile']);
        Route::post('update-profile', [ApiProfileController::class, 'updateProfile']);
        Route::post('update-phone-number', [ApiProfileController::class, 'updatePhoneNumber']);
        Route::post('update-password', [ApiProfileController::class, 'updatePassword']);
        // notices
        Route::get('notices', [ApiNoticeController::class, 'index']);
        Route::post('notices', [ApiNoticeController::class, 'store']);
        Route::get('notices/{notice}', [ApiNoticeController::class, 'show']);
        Route::get('notices/{notice}/edit', [ApiNoticeController::class, 'edit']);
        Route::put('notices/{notice}', [ApiNoticeController::class, 'update']);
        Route::delete('notices/{notice}', [ApiNoticeController::class, 'destroy']);
        // panic button
        Route::get('panic-button', [ApiPanicButtonController::class, 'index']);
        Route::post('panic-button', [ApiPanicButtonController::class, 'store']);
        Route::post('panic-button/{id}/close', [ApiPanicButtonController::class, 'close']);
        // nomor penting
        Route::get('important-numbers', [ApiImportantNumberController::class, 'index']);
        Route::post('important-numbers', [ApiImportantNumberController::class, 'store']);
        Route::get('important-numbers/{number}/edit', [ApiImportantNumberController::class, 'edit']);
        Route::put('important-numbers/{number}', [ApiImportantNumberController::class, 'update']);
        Route::delete('important-numbers/{number}', [ApiImportantNumberController::class, 'destroy']);
        // Iuran
        Route::get('iuran', [ApiDonationController::class, 'index']);
        Route::get('iuran/{donation}', [ApiDonationController::class, 'show']);
        Route::post('iuran/{donation}/bill', [ApiDonationController::class, 'storeBill']);
        Route::post('iuran', [ApiDonationController::class, 'store']);
        Route::get('iuran/{donation}/edit', [ApiDonationController::class, 'edit']);
        Route::put('iuran/{donation}', [ApiDonationController::class, 'update']);
        Route::delete('iuran/{donation}', [ApiDonationController::class, 'destroy']);
        // Iuran Bill
        Route::get('iuran-bills', [ApiDonationBillController::class, 'index']);
        Route::get('iuran-bills/{id}', [ApiDonationBillController::class, 'show']);
        // complaint
        Route::get('history-complaint', [ApiComplaintController::class, 'history']);
        Route::get('complaint', [ApiComplaintController::class, 'index']);
        Route::post('complaint', [ApiComplaintController::class, 'store']);
        Route::post('complaint-document', [ApiComplaintController::class, 'storeDocument']);
        Route::get('complaint/{complaint}', [ApiComplaintController::class, 'show']);
        // complaint admin
        Route::get('pengurus-complaint', [ApiComplaintController::class, 'indexAdmin']);
        Route::post('pengurus-complaint/{id}', [ApiComplaintController::class, 'updateStatusComplaint']);
        // cover letter
        Route::get('cover-letter', [ApiCoverLetterController::class, 'index']);
        Route::get('data-family', [ApiCoverLetterController::class, 'getDataFamily']);
        Route::post('cover-letter', [ApiCoverLetterController::class, 'store']);
        Route::post('download-cover-letter-{id}', [ApiCoverLetterController::class, 'downloadCoverLetter']);
        Route::get('pengurus-cover-letter', [ApiCoverLetterController::class, 'indexAdmin']);
        Route::post('pengurus-cover-letter-{id}', [ApiCoverLetterController::class, 'updateStatusCoverLetter']);
        Route::get('cover-letter/{id}/edit', [ApiCoverLetterController::class, 'edit']);
        Route::put('cover-letter/{id}', [ApiCoverLetterController::class, 'update']);
        Route::delete('cover-letter/{id}', [ApiCoverLetterController::class, 'destroy']);
        // polling
        Route::get('polling', [ApiPollingController::class, 'index']);
        Route::get('polling/{id}', [ApiPollingController::class, 'show']);
        Route::post('polling', [ApiPollingController::class, 'store']);
        Route::post('create-polling', [ApiPollingController::class, 'createPolling']);
        Route::get('polling/{id}/edit', [ApiPollingController::class, 'edit']);
        Route::put('polling/{id}', [ApiPollingController::class, 'update']);
        Route::delete('polling/{id}', [ApiPollingController::class, 'destroy']);
        Route::post('polling/{id}/start', [ApiPollingController::class, 'startPolling']);
        Route::post('polling/{id}/finish', [ApiPollingController::class, 'finishPolling']);
    });
});
