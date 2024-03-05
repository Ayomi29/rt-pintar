<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiNoticeTest extends TestCase
{
    use DatabaseTransactions;
    public function test_api_get_data_notice(): void
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->get('/api/v1/notices');
        $response->assertStatus(200);
    }
    public function test_api_get_detail_data_notice()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->get('/api/v1/notices/1');
        $response->assertStatus(200);
    }
    public function test_api_add_notice()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $response = $this->post('/api/v1/notices', [
            'title' => 'add new',
            'description' => 'new notice',
        ]);
        $response->assertStatus(200);
    }
}
