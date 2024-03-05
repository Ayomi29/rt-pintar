<?php

namespace Tests\Feature;

use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use DatabaseTransactions;
    public function test_api_login(): void
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081234567890',
            'password' => 'warga123',
        ]);
        // Check if the login was successful
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }
    public function test_api_check_family_card()
    {
        $response = $this->post('api/v1/check-family-card', [
            'family_card_number' => 1111111111111111
        ]);
        $response->assertStatus(200);
    }
    public function test_api_register()
    {
        $response = $this->post('/api/v1/register', [
            'email' => 'satriaayomi@gmail.com',
            'phone_number' => '081256418571',
            'password' => 'ayomi123',
            'family_member_id' => '13'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['phone_number' => '081256418571']);
    }
    public function test_api_confirm_phone_number()
    {
        $response = $this->post('/api/v1/confirm-phone-number', [
            'phone_number' => '081234567890'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('otp_codes', ['user_id' => 2]);
    }
    public function test_api_change_password()
    {
        $response = $this->post('/api/v1/change-password', [
            'code' => '320398',
            'password' => 'warga12'
        ]);
        $response->assertStatus(200);
    }
    public function test_verify_account()
    {
        $user = User::where('phone_number', '081257418571')->first();
        $response = $this->actingAs($user)->post('/api/v1/confirm-otp', [
            'code' => '123423'
        ]);
        $response->assertFound();
        
    }
    public function test_user_can_logout()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $resp = $this->post('/api/v1/logout');
        $resp->assertStatus(200);
    }
}
