<?php

namespace Tests\Feature;

use App\Models\ImportantNumber;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ImportantNumberTest extends TestCase
{
    use DatabaseTransactions;
    public function test_access_important_number_management_page(): void
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/important-numbers');
        $response->assertSee('Manajemen Nomor Penting');
        $response->assertStatus(200);
    }
    public function test_admin_can_see_add_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/important-numbers');
        $response->assertSee('Tambah');
    }
    public function test_admin_can_see_edit_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/important-numbers');
        $response->assertSee('Ubah');
    }
    public function test_admin_can_see_delete_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/important-numbers');
        $response->assertSee('Hapus');
    }
    
    public function test_admin_can_add_important_number_data()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->post('/important-numbers', [
            'name' => 'nomor tes',
            'phone_number' => '081234'
        ]);
        
        $response->assertFound();
        $this->assertDatabaseHas('important_numbers', ['name' => 'nomor tes']);
    }
    public function test_admin_can_see_data_for_edit_important_number()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $imp_num = ImportantNumber::where('phone_number', '0812357214')->first();
        $response = $this->actingAs($user)->get('/important-numbers/' . $imp_num->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee($imp_num->phone_number);
    }
    public function test_admin_can_update_data_important_number()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $imp_num = ImportantNumber::where('phone_number', '0812357214')->first();
        $response = $this->actingAs($user)->put('/important-numbers/'.$imp_num->id, [
            'name' => 'nomor test',
            'phone_number' => '0812357214'
        ]);
        // dd($response);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
    }
    public function test_admin_can_delete_data_important_number()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $imp_num = ImportantNumber::where('phone_number', '0812357214')->first();
        $response = $this->actingAs($user)->delete('/important-numbers/'.$imp_num->id);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
        $this->assertDatabaseMissing('important_numbers', ['phone_number' => '0812357214']);
    }
}
