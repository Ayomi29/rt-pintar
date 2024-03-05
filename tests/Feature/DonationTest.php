<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DonationTest extends TestCase
{
    use DatabaseTransactions;
    public function test_access_donation_management_page(): void
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/iurans');
        $response->assertSee('Manajemen Iuran');
        $response->assertStatus(200);
    }
    public function test_admin_can_see_add_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/iurans');
        $response->assertSee('Tambah');
    }
    public function test_admin_can_see_edit_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/iurans');
        $response->assertSee('Ubah');
    }
    public function test_admin_can_see_delete_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/iurans');
        $response->assertSee('Hapus');
    }
    
    public function test_admin_can_add_donation_data()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        Storage::fake('local');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->actingAs($user)->post('/iurans', [
            'title' => 'ini hanya test',
            'description' => 'test',
            'nominal' => 'Rp. 20.0000',
            'image' => $file
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('donations', ['title' => 'ini hanya test']);
    }
    public function test_admin_can_see_data_for_edit_donation()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $donation = Donation::where('title', 'test donation')->first();
        $response = $this->actingAs($user)->get('/iurans/' . $donation->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee($donation->title);
    }
    public function test_admin_can_update_data_donation()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $donation = Donation::where('title', 'test donation')->first();
        Storage::fake('local');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->actingAs($user)->put('/iurans/'.$donation->id, [
            'title' => 'test donation',
            'description' => 'test donation',
            'nominal' => 'Rp. 2.000',
            'image' => $file
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
    }
    public function test_admin_can_delete_data_donation()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $donation = Donation::where('title', 'test donation')->first();
        $response = $this->actingAs($user)->delete('/iurans/'.$donation->id);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
        $this->assertDatabaseMissing('donations', ['title' => 'test donation']);
    }
}
