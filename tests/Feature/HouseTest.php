<?php

namespace Tests\Feature;

use App\Models\House;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Testing\Concerns\AssertsStatusCodes;
use Tests\TestCase;

class HouseTest extends TestCase
{
    use DatabaseTransactions;
    public function test_access_house_management_page(): void
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/houses');
        $response->assertSee('Manajemen rumah');
        $response->assertStatus(200);
    }
    public function test_admin_can_see_add_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/houses');
        $response->assertSee('Tambah');
    }
    public function test_admin_can_see_edit_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/houses');
        $response->assertSee('Ubah');
    }
    public function test_admin_can_add_house_data_with_family_card_number()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->post('/houses', [
            'house_number' => 99,
            'family_card_number'=> 3218952348751321
        ]);
        $response->assertFound();
        $this->assertDatabaseHas('houses', ['house_number' => 99]);
        $this->assertDatabaseHas('family_cards', ['family_card_number'=> 3218952348751321]);
    }
    public function test_admin_can_add_house_data_without_family_card_number()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->post('/houses', [
            'house_number' => 98
        ]);
        $response->assertFound();
        $this->assertDatabaseHas('houses', ['house_number' => 98]);
    }
    public function test_admin_can_see_data_for_edit_house()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $house = House::where('house_number', '7')->first();
        // dd($house);
        $response = $this->actingAs($user)->get('/houses/' . $house->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee($house->house_number);
    }
    public function test_admin_can_update_data_house()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $house = House::where('house_number', '7')->first();
        // dd($house);
        $response = $this->actingAs($user)->put('/houses/'.$house->id, [
            'house_number' => 10,
            'longitude' => '',
            'latitude' => ''
        ]);
        // dd($response);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
    }
    
}
