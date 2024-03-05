<?php

namespace App\Http\Controllers;

use App\Mail\MailCodeOTP;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class AuthenticationController extends Controller
{

public function index()
{
    return view('authentication.index');
}

public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required'],
        'password' => ['required'],
    ]);
    if (Auth::attempt($credentials)) {
        if (Auth::user()->admin == true) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
        } else {
            return redirect()->back()->with('ERR', 'Anda tidak memiliki hak akses admin!!!');
        }
    }
    return back()->withErrors(['email'=> 'The provided credentials do not match our records.'])->onlyInput();
}

public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('');
}
public function sendEmailOtpAdmin()
{
    if (isset($_POST['email'])) {
        $usedEmail = User::where('email', $_POST['email'])->first();
        if ($usedEmail == null) {
            $response = "email yang anda masukkan salah";
        } else {
            $response['email'] = $usedEmail->email;
            $response['message'] = "kode otp berhasil dikirim";
            $existing_otp_code = OtpCode::where('user_id', $usedEmail->id)->first();
            if ($existing_otp_code != null) {
                $existing_otp_code->delete();
            }
            $otp_code = OtpCode::create([
                'user_id' => $usedEmail->id,
                'code' => rand(100000, 999999)
            ]);
            $code = $otp_code->code;
            Mail::to($usedEmail->email)->send(new MailCodeOTP($code));
        }
    }
    return $response;
}

public function confirmOtpAdmin()
{
    if (isset($_POST['code'])) {
        $usedEmail = User::where('email', $_POST['email'])->orWhere('phone_number', $_POST['email'])->first();
        $usedCode = OtpCode::where([['code', $_POST['code']], ['user_id', $usedEmail->id]])->first();
        if ($usedCode == null) {
            $response = "kode otp tidak valid";
        } else {
            $response = "berhasil menkonfirmasi kode otp";
        }
    }
    return $response;
}
public function changePassword()
{
    $usedEmail = User::where('email', request('email'))->orWhere('phone_number', request('email'))->first();
    $usedEmail->update([
        'password' => bcrypt(request('password'))
    ]);
    $usedCode = OtpCode::where('user_id', $usedEmail->id)->first();
    $usedCode->delete();

    return redirect()->route('login')->with('OK', 'Berhasil mengubah password');
}
}
