<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiCoverLetterTest extends TestCase
{
    use DatabaseTransactions;
    public function test_get_data_cover_letter(): void
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $resp = $this->get('/api/v1/cover-letter');
        $resp->assertStatus(200);
    }
    public function test_get_data_family(): void
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $resp = $this->get('/api/v1/data-family');
        $resp->assertStatus(200);
    }
    public function test_warga_request_cover_letter()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $resp = $this->post('/api/v1/cover-letter', [
            'family_member_id' => 1,
            'description' => 'surat pengantar',
            'title' => 'SURAT PENGANTAR (Ahli Waris)'
        ]);
        $resp->assertStatus(200);
    }
    public function test_get_all_data_cover_letter_for_admin()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $resp = $this->get('/api/v1/pengurus-cover-letter');
        $resp->assertStatus(200);
    }
    public function test_update_status_data_cover_letter_for_admin()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $resp = $this->post('/api/v1/pengurus-cover-letter-2');
        $resp->assertStatus(200);
    }
    
}
