<?php

namespace Tests\Feature;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoticeTest extends TestCase
{
    use DatabaseTransactions;
    public function test_access_notices_management_page(): void
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/notices');
        
        $response->assertStatus(200);
    }
    public function test_admin_can_see_add_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/notices');
        $response->assertSee('Tambah');
    }
    public function test_admin_can_see_edit_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/notices');
        $response->assertSee('Ubah');
    }
    public function test_admin_can_see_delete_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/notices');
        $response->assertSee('Hapus');
    }
    
    public function test_admin_can_add_notice_data()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->post('/notices', [
            'title' => 'Test pengumuman',
            'description' => 'test'
        ]);
        $response->assertFound();
        $this->assertDatabaseHas('notices', ['title' => 'Test pengumuman']);
    }
    public function test_admin_can_see_data_for_edit_notice()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $notice = Notice::where('title', 'New pengumuman')->first();
        $response = $this->actingAs($user)->get('/notices/' . $notice->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee($notice->title);
    }
    public function test_admin_can_update_data_notice()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $notice = Notice::where('title', 'New pengumuman')->first();
        $response = $this->actingAs($user)->put('/notices/'.$notice->id, [
            'title' => 'test',
            'description' => 'test',
            'status' => 'aktif'
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
    }
    public function test_admin_can_delete_data_notice()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $notice = Notice::where('title', 'New pengumuman')->first();
        $response = $this->actingAs($user)->delete('/notices/'.$notice->id);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
        $this->assertDatabaseMissing('notices', ['title' => 'New pengumuman']);
    }
}
