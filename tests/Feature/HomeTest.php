<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    
    public function test_access_homepage(): void
    {
        $response = $this->get('/home');
        $response->assertFound();
    }
    
}
