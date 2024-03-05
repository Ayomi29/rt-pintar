<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiPollingTest extends TestCase
{
    use DatabaseTransactions;
    public function test_get_data_polling(): void
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $resp = $this->get('/api/v1/polling');
        $resp->assertStatus(200);
    }
    public function test_get_detail_data_polling(): void
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $resp = $this->get('/api/v1/polling/7');
        $resp->assertStatus(200);
    }
    public function test_vote_polling()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $resp = $this->post('/api/v1/polling', [
            'polling_option_id' => 1
        ]);
        $resp->assertStatus(200);
    }
    public function test_create_new_polling()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $resp = $this->post('/api/v1/create-polling', [
            'title' => 'testing polling di phpunit',
            'description' => 'tes fitur',
            'option_name' => ['tentu iya', 'tentu tidak']
        ]);
        $resp->assertStatus(200);

    }
}
