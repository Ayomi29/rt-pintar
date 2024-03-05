<?php

namespace Tests\Feature;

use App\Models\OtpCode;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
class AuthTest extends TestCase
{
    public function test_get_login_page(): void
    {
        // dd(config('app.env'));
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    public function test_users_can_authenticate()
    {

        $response = $this->post('/', [
            'email' => 'admnrt15balikpapan@gmail.com',
            'password' => 'admin1234',
        ]);
        $this->assertAuthenticated();
     
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {

        $this->post('/', [
            'email' => 'admnrt15balikpapan@gmail.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
    public function test_confirm_email_change_passsword()
    {
        $email = ['email' => 'admnrt15balikpapan@gmail.com']; 
        
        $this->assertDatabaseHas('users', $email);    
    }
    public function test_cannot_change_passsword_with_invalid_email()
    {
        $this->assertDatabaseMissing('users', [
            'email'=>''
        ]);
    }
    public function test_confirm_otp_code()
    {
        $otp = OtpCode::create([
            'user_id' => 1,
            'code' => 130471
        ]);
        $code = ['code' => 130471];
        $this->assertDatabaseHas('otp_codes', $code);
    }
    public function test_change_password()
    {
        $resp = $this->post('/change-password', [
            'email' => 'admnrt15balikpapan@gmail.com',
            'password' => 'admin1234'
        ]);
        $resp->assertFound();
    }
    public function test_user_can_logout()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->post('/logout');
        $response->assertFound();
    }

}
