<?php

namespace Tests\Feature;

use App\Models\CoverLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CoverLetterTest extends TestCase
{
    use DatabaseTransactions;
    public function test_access_cover_letter_management_page(): void
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/cover-letters');
        $response->assertSee('Manajemen Surat Pengantar');
        $response->assertSee('SURAT PENGANTAR');
        $response->assertStatus(200);
    }
    public function test_admin_can_see_add_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/cover-letters');
        $response->assertSee('Tambah');
    }
    public function test_admin_can_see_edit_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/cover-letters');
        $response->assertSee('Ubah');
    }
    public function test_admin_can_see_delete_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/cover-letters');
        $response->assertSee('Hapus');
    }
    
    public function test_admin_can_make_cover_letter_for_the_residents()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->post('/cover-letters', [
            'family_member_id' => 3,
            'title' => 'SURAT PENGANTAR',
            'description' => 'surat pengantar buat test'
        ]);
        
        $response->assertFound();
        $this->assertDatabaseHas('cover_letters', [
            'family_member_id' => 3,
            'description' => 'surat pengantar buat test'
        ]);
    }
    public function test_admin_can_see_the_data_for_editing_the_cover_letter()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $coverLetter = CoverLetter::where(['family_member_id' => 8, 'letter_number' => 4])->first();
        $response = $this->actingAs($user)->get('/cover-letters/' . $coverLetter->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee($coverLetter->family_member_id);
        $response->assertSee($coverLetter->title);
    }
    public function test_admin_can_update_status_data_cover_letter()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $coverLetter = CoverLetter::where(['family_member_id' => 8, 'letter_number' => 4])->first();
        $response = $this->actingAs($user)->put('/cover-letters/'.$coverLetter->id, [
            'family_member_id' => 8,
            'title' => 'SURAT PENGANTAR (Ahli Waris)',
            'description' => 'surat pengantar hanya test',
            'status' => 'diterima'
        ]);
        // dd($response);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('cover_letters', [
            'family_member_id' => 8,
            'description' => 'surat pengantar hanya test'
        ]);
        $this->assertTrue(true);
    }
    public function test_admin_can_delete_data_cover_letter()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $coverLetter = CoverLetter::where(['family_member_id' => 8, 'letter_number' => 4])->first();
        $response = $this->actingAs($user)->delete('/cover-letters/'.$coverLetter->id);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
        $this->assertDatabaseMissing('cover_letters', ['family_member_id' => 8, 'letter_number' => 4]);
    }
}
