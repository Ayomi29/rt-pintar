<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiPanicButtonTest extends TestCase
{
    use DatabaseTransactions;
    public function test_get_data_panic_button_with_status_menunggu(): void
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $resp = $this->get('/api/v1/panic-button');
        $resp->assertStatus(200);
    }
    public function test_click_panic_button()
    {
        $response = $this->post('/api/v1/login-warga', [
            'phone_number' => '081257418596',
            'password' => 'admin1234',
        ]);
        $token = $response->json('token');
        $this->withHeader('Authorization', 'Bearer' . $token);
        $resp = $this->post('/api/v1/panic-button');
        $resp->assertStatus(200);
    }
    // public function test_close_panic_button()
    // {
    //     $response = $this->post('/api/v1/login-warga', [
    //         'phone_number' => '081257418596',
    //         'password' => 'admin1234',
    //     ]);
    //     $token = $response->json('token');
    //     $this->withHeader('Authorization', 'Bearer' . $token);
    //     $resp = $this->post('/api/v1/panic-button/3/close', [
    //         'status' => 'selesai',
    //         'description' => 'end'
    //     ]);
    //     $resp->assertStatus(200);

    // }
}
