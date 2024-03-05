<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    public function test_access_user_management_page(): void
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/users');
        $response->assertSee('Manajemen Data User');
        $response->assertStatus(200);
    }
    public function test_admin_can_see_add_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/users');
        $response->assertSee('Tambah');
    }
    public function test_admin_can_see_edit_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/users');
        $response->assertSee('Ubah');
    }
    public function test_admin_can_see_delete_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/users');
        $response->assertSee('Hapus');
    }
    
    public function test_admin_can_add_data_user()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->post('/users', [
            'nik' => 1313131313131313,
            'email' => 'anak@gmail.com',
            'phone_number' => '0888774142',
            'password' => 'anak123'
        ]);
        
        $response->assertFound();
        $this->assertDatabaseHas('users', ['phone_number' => '0888774142']);
    }
    public function test_admin_can_see_data_for_edit_user()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $pengguna = User::where('id', 2)->first();
        $response = $this->actingAs($user)->get('/users/' . $pengguna->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee($pengguna->user_id);
    }
    public function test_admin_can_update_data_user()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $pengguna = User::where('id', 2)->first();
        $response = $this->actingAs($user)->put('/users/'.$pengguna->id, [
            'phone_number' => '0888774143',
            'password' => 'anak123',
            'status' => 1
        ]);
        // dd($response);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'id' => 2,
            'phone_number' => '0888774143'
        ]);
        $this->assertTrue(true);
    }
    public function test_admin_can_delete_data_user()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $pengguna = User::where('id', 2)->first();
        $response = $this->actingAs($user)->delete('/users/'.$pengguna->id);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
        $this->assertDatabaseMissing('users', ['id' => '2']);
    }
}
