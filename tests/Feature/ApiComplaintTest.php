<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApiComplaintTest extends TestCase
{
    use DatabaseTransactions;
    public function test_get_data_complaints_with_status_diposting_and_selesai(): void
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->get('/api/v1/complaint');
        $response->assertStatus(200);
    }
    public function test_get_all_history_data_complaints_user()   
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->get('/api/v1/history-complaint');
        $response->assertStatus(200);
    }
    public function test_get_detail_data_complaint()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->get('/api/v1/complaint/1');
        $response->assertStatus(200);
    }
    public function test_store_data_complaint()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->get('/api/v1/complaint', [
            'title' => 'Aduan baru',
            'description' => 'adu'
        ]);
        $response->assertStatus(200);
    }
    public function test_store_document_complaint()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        Storage::fake('local');
        $img = UploadedFile::fake()->create('img.jpg');
        $response = $this->get('/api/v1/complaint', [
            'complaint_id' => 2,
            'document' => $img
        ]);
        $response->assertStatus(200);
    }
    public function test_get_all_users_complaints_for_admin()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->get('/api/v1/pengurus-complaint');
        $response->assertStatus(200);
    }
    public function test_update_status_complaints_for_admin()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->post('/api/v1/pengurus-complaint/1');
        $response->assertStatus(200);
    }
    
}
