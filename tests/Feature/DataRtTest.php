<?php

namespace Tests\Feature;

use App\Models\DataRt;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DataRtTest extends TestCase
{
    use DatabaseTransactions;
    public function test_access_data_rt_management_page(): void
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/data-rts');
        $response->assertSee('Manajemen Data RT');
        $response->assertStatus(200);
    }
    public function test_admin_can_see_add_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/data-rts');
        $response->assertSee('Tambah');
    }
    public function test_admin_can_see_edit_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/data-rts');
        $response->assertSee('Ubah');
    }
    public function test_admin_can_see_delete_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/data-rts');
        $response->assertSee('Hapus');
    }
    
    public function test_admin_can_add_data_rt_data()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        Storage::fake('local');
        $logo = UploadedFile::fake()->image('avatar.jpg');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->actingAs($user)->post('/data-rts', [
            'name_lead_rt' => 'bu rt',
            'logo_rt' => $logo,
            'sign_rt' => $file
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('data_rts', ['name_lead_rt' => 'bu rt']);
    }
    public function test_admin_can_see_data_for_edit_data_rt()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $data_rt = DataRt::where('name_lead_rt', 'Pak RT')->first();
        $response = $this->actingAs($user)->get('/data-rts/' . $data_rt->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee($data_rt->name_lead_rt);
    }
    public function test_admin_can_update_data_data_rt()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $data_rt = DataRt::where('name_lead_rt', 'Pak RT')->first();
        Storage::fake('local');
        $logo = UploadedFile::fake()->image('avatar.jpg');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $response = $this->actingAs($user)->put('/data-rts/'.$data_rt->id, [
            'name_lead_rt' => 'pak rt',
            'logo_rt' => $logo,
            'sign_rt' => $file
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
    }
    public function test_admin_can_delete_data_data_rt()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $data_rt = DataRt::where('name_lead_rt', 'Pak RT')->first();
        $response = $this->actingAs($user)->delete('/data-rts/'.$data_rt->id);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
        $this->assertDatabaseMissing('data_rts', ['name_lead_rt' => 'Pak RT']);
    }
}
