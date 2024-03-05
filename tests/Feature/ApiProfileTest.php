<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApiProfileTest extends TestCase
{
    use DatabaseTransactions;
    public function test_api_get_data_profile(): void
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->get('/api/v1/profile');
        $response->assertStatus(200);
    }
    public function test_api_post_photo_profile()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        Storage::fake('local');
        $img = UploadedFile::fake()->create('foto.jpg');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->post('/api/v1/update-profile', [
            'avatar' => $img
        ]);
        $response->assertStatus(200);
    }
    public function test_api_update_phone_number()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->post('/api/v1/update-phone-number', [
            'phone_number' => '0812128899'
        ]);
        $response->assertStatus(200);
    }
    public function test_api_update_password()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->post('/api/v1/update-phone-number', [
            'password' => 'admin123'
        ]);
        $response->assertStatus(200);
    }
}
