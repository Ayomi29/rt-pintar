<?php

namespace Tests\Feature;

use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FamilyMemberTest extends TestCase
{
    use DatabaseTransactions;
    public function test_access_family_member_management_page(): void
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/family-members');
        $response->assertSee('Manajemen Data Anggota KK');
        $response->assertStatus(200);
    }
    public function test_admin_can_see_add_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/family-members');
        $response->assertSee('Tambah');
    }
    public function test_admin_can_see_edit_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/family-members');
        $response->assertSee('Ubah');
    }
    public function test_admin_can_see_delete_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/family-members');
        $response->assertSee('Hapus');
    }
    
    public function test_admin_can_add_family_member_data()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->post('/family-members', [
            'family_card_id' => 1,
            'family_member_name' => 'ayomi',
            'family_status' => 'anak',
            'nik' => '1212121212121212',
            'gender' => 'laki-laki',
            'birth_place' => 'balikpapan',
            'birth_date' => '2000-12-29',
            'job' => 'pelajar',
            'religious' => 'islam',
            'education' => 'SLTA/Sedarajat',
            'citizenship' => 'wni',
            'marital_status' => 'belum kawin',
            'address' => 'jalan balikpapan'
        ]);
        
        $response->assertStatus(302);
        $this->assertDatabaseHas('family_members', ['family_member_name' => 'ayomi']);
    }
    public function test_admin_can_see_data_for_edit_family_member()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $family_member = FamilyMember::where('family_member_name', 'anak bungsu')->first();
        $response = $this->actingAs($user)->get('/family-members/' . $family_member->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee($family_member->family_member_name);
    }
    public function test_admin_can_update_data_family_member()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $family_member = FamilyMember::where('family_member_name', 'anak bungsu')->first();
        $response = $this->actingAs($user)->put('/family-members/'.$family_member->id, [
            'family_member_name' => 'anak bungsu',
            'family_status' => 'anak',
            'nik' => '1212121212121212',
            'gender' => 'laki-laki',
            'birth_place' => 'balikpapan',
            'birth_date' => '2001-12-22',
            'job' => 'pelajar',
            'religious' => 'islam',
            'education' => 'SLTA/Sedarajat',
            'citizenship' => 'wni',
            'marital_status' => 'belum kawin',
            'address' => 'jalan balikpapan',
            'status' => 'aktif'
        ]);
        
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
    }
    public function test_admin_can_delete_data_family_member()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $family_member = FamilyMember::where('family_member_name', 'anak bungsu')->first();
        $response = $this->actingAs($user)->delete('/family-members/'.$family_member->id);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
        $this->assertDatabaseMissing('family_members', ['family_member_name' => 'anak bungsu']);
    }
}
