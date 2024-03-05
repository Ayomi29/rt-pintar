<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApiDonationTest extends TestCase
{
    use DatabaseTransactions;
    public function test_get_data_iuran(): void
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->get('/api/v1/iuran');
        $response->assertStatus(200);
    }
    public function test_get_detail_data_iuran(): void
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->get('/api/v1/iuran/1');
        $response->assertStatus(200);
    }
    public function test_get_data_iuran_bill_for_admin(): void
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->get('/api/v1/iuran-bills');
        $response->assertStatus(200);
    }
    public function test_get_detail_data_iuran_bill_for_admin(): void
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->get('/api/v1/iuran-bills/1');
        $response->assertStatus(200);
    }
    public function test_post_iuran_bill(): void
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        Storage::fake('local');
        $img = UploadedFile::fake()->create('foto.jpg');
        
        $response = $this->post('/api/v1/iuran/1/bill', [
            'donation_id' => 3,
            'nominal' => 'Rp. 10.000',
            'file' => $img
        ]);
        $response->assertStatus(200);
    }
    
}
