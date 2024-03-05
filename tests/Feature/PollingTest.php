<?php

namespace Tests\Feature;

use App\Models\Polling;
use App\Models\PollingOption;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PollingTest extends TestCase
{
    use DatabaseTransactions;
    public function test_access_polling_management_page(): void
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/pollings');
        $response->assertSee('Manajemen Polling');
        $response->assertSee('test polling');
        $response->assertStatus(200);
    }
    public function test_admin_can_see_add_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/pollings');
        $response->assertSee('Tambah');
    }
    public function test_admin_can_see_edit_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/pollings');
        $response->assertSee('Ubah');
    }
    public function test_admin_can_see_delete_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/pollings');
        $response->assertSee('Hapus');
    }
    public function test_admin_can_see_start_polling_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/pollings');
        $response->assertSee('Mulai Polling');
    }
    public function test_admin_can_see_end_polling_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/pollings');
        $response->assertSee('Akhiri Polling');
    }
    public function test_admin_can_add_data_polling()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->post('/pollings', [
            'title' => 'testing polling di phpunit',
            'description' => 'tes fitur',
            'option_name' => ['tentu iya', 'tentu tidak']
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('pollings', [
            'title' => 'testing polling di phpunit',
            'description' => 'tes fitur'
        ]);
        $this->assertDatabaseHas('polling_options', [
            'option_name' => 'tentu iya'
        ]);
    }
    public function test_admin_can_see_the_data_for_editing_polling()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $poll = Polling::where('title', 'Polling baru')->first();
        $response = $this->actingAs($user)->get('/pollings/' . $poll->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee($poll->title);
        $response->assertSee($poll->description);
    }
    public function test_admin_can_update_polling_data()
    {$user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $poll = Polling::where('title', 'Polling baru')->first();
        $response = $this->actingAs($user)->put('/pollings/' . $poll->id, [
            'title' => 'polling baru',
            'description' => 'tes fitur',
            'option_name' => ['tentu iya', 'tentu tidak']
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('pollings', [
            'title' => 'polling baru',
            'description' => 'tes fitur',
        ]);
        $this->assertDatabaseHas('polling_options', [
            'option_name' => 'tentu tidak'
        ]);
        $this->assertTrue(true);
    }
    public function test_admin_can_delete_polling()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $poll = Polling::where('title', 'Polling baru')->first();
        $response = $this->actingAs($user)->delete('/pollings/' . $poll->id);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseMissing('pollings', [
            'title' => 'Polling baru'
        ]);
    }
    public function test_admin_can_start_polling()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $poll = Polling::where('title', 'Polling baru')->first();
        $resp = $this->actingAs($user)->post('/pollings/' . $poll->id . '/start');
        $resp->assertStatus(302);
        $this->assertDatabaseHas('pollings', [
            'title' => 'Polling baru',
            'status' => 'start'
        ]);
    }
    public function test_admin_can_end_polling()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $poll = Polling::where('title', 'my polling 2')->first();
        $resp = $this->actingAs($user)->post('/pollings/' . $poll->id . '/finish');
        $resp->assertStatus(302);
        $this->assertDatabaseHas('pollings', [
            'title' => 'my polling 2',
            'status' => 'finish'
        ]);
    }

}
