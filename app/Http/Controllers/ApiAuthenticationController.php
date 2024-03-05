<?php

namespace App\Http\Controllers;

use App\Mail\MailCodeOTP;
use App\Mail\ResetPasswordEmail;
use App\Models\ActivityHistory;
use App\Models\DashboardNotification;
use App\Models\FamilyCard;
use App\Models\FamilyMember;
use App\Models\OtpCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ApiAuthenticationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['loginWarga', 'register', 'CheckFamilyCard', 'confirmPhoneNumber', 'confirmOtpResetPassword', 'changePassword']]);
    }
    public function CheckFamilyCard()
    {
        if (request('family_card_number') == null || request('family_card_number') == '') {
            $status = 'error';
            $status_code = 400;
            $message = 'Semua input wajib diisi';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        } else {
            $family_card = FamilyCard::where('family_card_number', request('family_card_number'))->first();
            $family_members = FamilyMember::where('family_card_id', $family_card->id)->orderBy('id', 'asc')->get();
            $family_head = false;
            foreach ($family_members as $item) {
                if ($item->family_status == 'kepala keluarga') {
                    if ($item->user == true) {
                        if ($item->verified == 1) {
                            $family_head = true;
                        }
                    }
                }
            }

            if ($family_head == true) {
                $family_members = FamilyMember::where([['family_card_id', $family_card->id], ['user_id', null]])->orderBy('id', 'asc')->get();
            } else {
                $family_members = FamilyMember::where([['family_card_id', $family_card->id], ['family_status', 'kepala keluarga']])->get();
            }

            if ($family_card == null) {
                $status = 'error';
                $status_code = 404;
                $message = 'No KK tidak ditemukan';
                return response()->json(compact('status', 'status_code', 'message'), 404);
            } else {
                $status = 'success';
                $status_code = 'DBC-200';
                $message = 'Berhasil mendapatkan data KK anda';
                $data = [
                    'family_card' => $family_card,
                    'family_member' => $family_members
                ];
                return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
                // return response()->json(compact('status', 'status_code', 'message', 'family_card', 'family_member'), 200);
            }
        }
    }


    public function register()
    {
        if (request('password') == null || request('password') == '' || request('phone_number') == null || request('phone_number') == '' || request('family_member_id') == null || request('family_member_id') == '') {
            $status = 'error';
            $status_code = 400;
            $message = 'Semua input wajib diisi';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        } else {
            $family_member = FamilyMember::where('id', request('family_member_id'))->first();
            $unvailable_phone_number = User::where('phone_number', request('phone_number'))->first();
            if ($unvailable_phone_number != null) {
                if ($unvailable_phone_number->family_member != null) {
                    if ($unvailable_phone_number->family_member->verified != 1) {
                        $unvailable_phone_number->family_member->update([
                            'user_id' => null
                        ]);
                        $unvailable_phone_number->delete();
                    } else {
                        $status = 'error';
                        $status_code = 400;
                        $message = 'Nomor telepon tidak dapat digunakan';
                        return response()->json(compact('status', 'status_code', 'message'), 400);
                    }
                } else {
                    $status = 'error';
                    $status_code = 400;
                    $message = 'Nomor telepon tidak dapat digunakan';
                    return response()->json(compact('status', 'status_code', 'message'), 400);
                }
            }
            $user = User::create([
                'email' => request('email'),
                'phone_number' => request('phone_number'),
                'password' => bcrypt(request('password'))
            ]);
            $user->update([
                'fcm_token' => request('fcm_token')
            ]);

            $family_member->update([
                'user_id' => $user->id
            ]);

            $available_otp_code = OtpCode::whereHas('user', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();
            if ($available_otp_code != null) {
                $available_otp_code->delete();
            }
            $otp_code = OtpCode::create([
                'user_id'  =>  $user->id,
                'code' => rand(100000, 999999)
            ]);
            Mail::to($user->email)->send(new MailCodeOTP($otp_code));
            
            DashboardNotification::create([
                'category' => 'Warga',
                'description' => $family_member->family_member_name
            ]);
            $token = auth('api')->fromUser($user);
            $status = 'success';
            $status_code = 200;
            $message = 'Berhasil mendaftarkan akun';
            $data = [
                'user' => $user,
                'family_member' => $family_member
            ];
            return response()->json(compact('status', 'status_code', 'message', 'token', 'data', 'otp_code'), 200);
        }
    }

    public function confirmPhoneNumber()
    {
        $user = User::where('phone_number', request('phone_number'))->first();
        if ($user == null) {
            $status = 'error';
            $status_code = 404;
            $message = 'Nomor telepon tidak ditemukan';
            return response()->json(compact('status', 'status_code', 'message'), 404);
        } else {
            $available_otp_code = OtpCode::whereHas('user', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();
            if ($available_otp_code != null) {
                $available_otp_code->delete();
            }
            $otp_code = OtpCode::create([
                'user_id'  =>  $user->id,
                'code' => rand(100000, 999999)
            ]);
            $phone = request('phone_number');
            Mail::to($user->email)->send(new ResetPasswordEmail($otp_code));
            $status = 'success';
            $status_code = 200;
            $message = 'Kode OTP telah dikirim';
            $data = ['user' => $user];
            return response()->json(compact('status', 'status_code', 'message', 'data', 'otp_code'), 200);
        }
    }


    // public function confirmOtpResetPassword()
    // {
    //     $otp_code = OtpCode::where('code', request('code'))->first();
    //     $user = User::where('id', $otp_code->user_id)->first();
    //     if (request('code') != $otp_code->code) {
    //         $status = 'error';
    //         $status_code = 404;
    //         $message = 'Kode OTP yang anda masukkan tidak valid';
    //         return response()->json(compact('status', 'status_code', 'message'), 404);
    //     } else {
    //         $status = "success";
    //         $status_code = 200;
    //         $message = "Kode OTP valid";
    //         return response()->json(compact('status', 'status_code', 'message', 'user'), 200);
    //     }
    // }

    public function changePassword()
    {
        $otp_code = OtpCode::where('code', request('code'))->first();
        $user = User::where('id', $otp_code->user_id)->first();
        $user->update([
            'password' => bcrypt(request('password'))
        ]);

        $otp_code->delete();

        $status = "OK";
        $status_code = "DBC-200";
        $message = "Berhasil memperbarui password";
        return response()->json(compact('status', 'status_code', 'message', 'user'), 200);
    }

    public function confirmVerificationCode()
    {
        $user = User::findOrFail(auth('api')->user()->id);
        $otp_code = OtpCode::where('user_id', $user->id)->first();
        if (request('code') != $otp_code->code) {
            $status = 'error';
            $status_code = 404;
            $message = 'Kode OTP yang anda masukkan tidak valid';
            return response()->json(compact('status', 'status_code', 'message'), 404);
        }
        $user->family_member->update([
            'verified' => 1,
        ]);

        $this->setFcmToken($user->id);

        $status = "success";
        $status_code = 200;
        $message = "Berhasil memverifikasi akun";
        return response()->json(compact('status', 'status_code', 'message', 'user'), 200);
    }

    public function resendCodeVerification()
    {
        $user = User::findOrFail(auth('api')->user()->id);
        $available_otp_code = OtpCode::whereHas('user', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->first();
        if ($available_otp_code != null) {
            $available_otp_code->delete();
        }

        $otp_code = OtpCode::create([
            'user_id'  =>  $user->id,
            'code' => rand(100000, 999999)
        ]);

        $status = "success";
        $status_code = 200;
        $message = "Berhasil mengirim kode verifikasi ulang";
        return response()->json(compact('status', 'status_code', 'message', 'otp_code'), 200);
    }

    public function loginWarga()
    {
        if (request('phone_number') == null || request('phone_number') == '' || request('password') == null || request('password') == '') {
            $status = 'error';
            $status_code = 400;
            $message = 'Semua input wajib diisi';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        } else {
            $user = User::where('phone_number', request('phone_number'))->orWhere('email', request('phone_number'))->first();
            if ($user == null) {
                $status = 'error';
                $status_code = 404;
                $message = 'Nomor telepon / email yang anda masukkan tidak valid';
                return response()->json(compact('status', 'status_code', 'message'), 404);
            } else {
                if ($user->family_member->verified == 0) {
                    $status = 'ERR';
                    $status_code = 'DBC-406';
                    $message = 'Akun anda belum terverifikasi';
                    return response()->json(compact('status', 'status_code', 'message'), 406);
                } else {
                    $input = request('phone_number');
                    if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                        $credentials = [
                            'phone_number' => $user->email,
                            'password' => request('password')
                        ];
                    } else {
                        $credentials = ['phone_number' => $user->phone_number, 'password' => request('password')];
                    }
                    if (!$token = auth('api')->attempt($credentials)) {
                        $status = 'ERR';
                        $status_code = 'DBC-401';
                        $message = 'Password yang anda masukkan tidak valid';
                        return response()->json(compact('status', 'status_code', 'message'), 401);
                    } else {
                        ActivityHistory::create([
                            'user_id' => $user->id,
                            'description' => 'Login warga'
                        ]);
        
                        $user->update([
                            'fcm_token' => request('fcm_token')
                        ]);
                        // $this->setFcmToken($user->id);
                        $token = auth('api')->fromUser($user);
        
                        $status = 'success';
                        $status_code = 200;
                        $message = 'Berhasil login';
                        $data = ['user' => $user];
                        $token_type = 'bearer';
                        $tokenTTL = auth('api')->factory()->getTTL() * 60;
                        $expires_in = Carbon::now()->addSeconds($tokenTTL);
                        return response()->json(compact('status', 'status_code', 'message', 'data', 'token', 'token_type', 'expires_in'), 200);
                    }
                }
            }
        }
    }

    public function logout()
    {
        $user = User::findOrFail(auth('api')->user()->id);
        if ($user->family_member == true) {
            ActivityHistory::create([
                'user_id' => $user->id,
                'description' => 'Logout warga'
            ]);
        }
        auth('api')->logout();
        $this->destroyFcmToken($user->id);

        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil logout';
        return response()->json(compact('status', 'status_code', 'message'), 200);
    }


    public function destroyFcmToken($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->update([
            'fcm_token' => null
        ]);

        $status = 'success';
        $status_code = 200;
        $message = 'Token berhasil dihapus';
        return response()->json(compact('status', 'status_code', 'message', 'fcm_token'), 200);
    }
}
