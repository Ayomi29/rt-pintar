<?php

use App\Http\Controllers\ApiAuthenticationController;
use App\Http\Controllers\ApiComplaintController;
use App\Http\Controllers\ApiCoverLetterController;
use App\Http\Controllers\ApiDonationBillController;
use App\Http\Controllers\ApiDonationController;
use App\Http\Controllers\ApiImportantNumberController;
use App\Http\Controllers\ApiNoticeController;
use App\Http\Controllers\ApiPanicButtonController;
use App\Http\Controllers\ApiPollingController;
use App\Http\Controllers\ApiProfileController;
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
    Route::post('confirm-otp-reset-password', [ApiAuthenticationController::class, 'confirmOtpResetPassword']);
    Route::post('confirm-otp', [ApiAuthenticationController::class, 'confirmVerificationCode']);
    Route::post('reconfirm-otp', [ApiAuthenticationController::class, 'resendCodeVerification']);
    Route::get('tes-notif', [ApiPanicButtonController::class, 'tesNotif']);

    Route::middleware('jwt.auth')->group(function () {
        Route::post('logout', [ApiAuthenticationController::class, 'logout']);
        // profile
        Route::get('profile', [ApiProfileController::class, 'getProfile']);
        Route::post('update-profile', [ApiProfileController::class, 'updateProfile']);
        Route::post('update-phone-number', [ApiProfileController::class, 'updatePhoneNumber']);
        Route::post('update-password', [ApiProfileController::class, 'updatePassword']);
        // notices
        Route::get('notices', [ApiNoticeController::class, 'index']);
        Route::post('notices', [ApiNoticeController::class, 'store']);
        Route::get('notices/{notice}', [ApiNoticeController::class, 'show']);
        // panic button
        Route::get('panic-button', [ApiPanicButtonController::class, 'index']);
        Route::post('panic-button', [ApiPanicButtonController::class, 'store']);
        Route::post('panic-button/{id}/close', [ApiPanicButtonController::class, 'close']);
        // nomor penting
        Route::get('important-numbers', [ApiImportantNumberController::class, 'index']);
        // Iuran
        Route::get('iuran', [ApiDonationController::class, 'index']);
        Route::get('iuran/{donation}', [ApiDonationController::class, 'show']);
        Route::post('iuran/{donation}/bill', [ApiDonationController::class, 'storeBill']);
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
        // polling
        Route::get('polling', [ApiPollingController::class, 'index']);
        Route::get('polling/all', [ApiPollingController::class, 'indexAll']);
        Route::get('polling/{id}', [ApiPollingController::class, 'show']);
        Route::post('polling', [ApiPollingController::class, 'store']);
        Route::post('create-polling', [ApiPollingController::class, 'createPolling']);
    });
});
