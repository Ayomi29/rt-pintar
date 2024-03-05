<?php

namespace Tests\Feature;

use App\Models\FamilyCard;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FamilyCardTest extends TestCase
{
    use DatabaseTransactions;
    public function test_access_family_card_management_page(): void
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/family-cards');
        $response->assertSee('Manajemen Data Kartu Keluarga');
        $response->assertStatus(200);
    }
    public function test_admin_can_see_add_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/family-cards');
        $response->assertSee('Tambah');
    }
    public function test_admin_can_see_edit_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/family-cards');
        $response->assertSee('Ubah');
    }
    public function test_admin_can_see_delete_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/family-cards');
        $response->assertSee('Hapus');
    }
    
    public function test_admin_can_add_family_card_data()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->post('/family-cards', [
            'house_id' => 1,
            'family_card_number' => 1212121212121212
        ]);
        
        $response->assertStatus(302);
        $this->assertDatabaseHas('family_cards', ['family_card_number' => 1212121212121212]);
    }
    public function test_admin_can_see_data_for_edit_family_card()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $family_card = FamilyCard::where('family_card_number', '1111111111111111')->first();
        $response = $this->actingAs($user)->get('/family-cards/' . $family_card->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee($family_card->family_card_number);
    }
    public function test_admin_can_update_data_family_card()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $family_card = FamilyCard::where('family_card_number', '1111111111111111')->first();
        $response = $this->actingAs($user)->put('/family-cards/'.$family_card->id, [
            'house_id' => 1,
            'family_card_number' => 1111111111111111,
            'status' => 'aktif'
        ]);
        
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
    }
    public function test_admin_can_delete_data_family_card()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $family_card = FamilyCard::where('family_card_number', '1111111111111111')->first();
        $response = $this->actingAs($user)->delete('/family-cards/'.$family_card->id);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
        $this->assertDatabaseMissing('family_cards', ['family_card_number' => 1111111111111111]);
    }
}
