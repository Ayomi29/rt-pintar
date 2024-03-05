<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ComplaintTest extends TestCase
{
    use DatabaseTransactions;
    public function test_access_complaint_management_page(): void
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/complaints');
        $response->assertSee('Manajemen Aduan Warga');
        $response->assertStatus(200);
    }
    public function test_admin_can_see_documentation_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/complaints');
        $response->assertSee('Dokumentasi');
    }
    public function test_admin_can_see_delete_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/complaints');
        $response->assertSee('Hapus');
    }
    public function test_admin_can_see_edit_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/complaints');
        $response->assertSee('Ubah');
    }
    public function test_the_admin_can_view_the_documentation_data_related_to_complaint()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $complaint = Complaint::where('title', 'Sarang ular di rumah')->first();
        // dd($complaint);
        $response = $this->actingAs($user)->get('/complaints/' . $complaint->id);
        $response->assertStatus(200);
        $response->assertSee('Dokumen Aduan Warga');
    }
    public function test_admin_can_see_data_for_edit_complaint()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $complaint = Complaint::where('title', 'Sarang ular di rumah')->first();
        
        $response = $this->actingAs($user)->get('/complaints/' . $complaint->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee($complaint->title);
    }
    
    public function test_admin_can_update_data_complaint()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $complaint = Complaint::where('title', 'Sarang ular di rumah')->first();
        // dd($complaint);
        $response = $this->actingAs($user)->put('/complaints/'.$complaint->id, [
            'status' => 'diselidiki'
        ]);
        // dd($response);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
    }
    public function test_admin_can_delete_data_complaint()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $complaint = Complaint::where('title', 'Sarang ular di rumah')->first();
        $response = $this->actingAs($user)->delete('/complaints/'.$complaint->id);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
        $this->assertDatabaseMissing('complaints', ['title' => 'Sarang ular di rumah']);
    }
}
