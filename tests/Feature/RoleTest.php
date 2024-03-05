<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use DatabaseTransactions;
    public function test_access_role_management_page(): void
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/roles');
        $response->assertSee('Manajemen Data Role User');
        $response->assertStatus(200);
    }
    public function test_admin_can_see_add_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/roles');
        $response->assertSee('Tambah');
    }
    public function test_admin_can_see_edit_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/roles');
        $response->assertSee('Ubah');
    }
    public function test_admin_can_see_delete_button()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->get('/roles');
        $response->assertSee('Hapus');
    }
    
    public function test_admin_can_add_data_role()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $response = $this->actingAs($user)->post('/roles', [
            'user_id' => 3,
            'role_name' => 'pengurus'
        ]);
        
        $response->assertFound();
        $this->assertDatabaseHas('roles', ['user_id' => 3]);
    }
    public function test_admin_can_see_data_for_edit_role()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $role = Role::where('user_id', 2)->first();
        $response = $this->actingAs($user)->get('/roles/' . $role->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee($role->user_id);
    }
    public function test_admin_can_update_data_role()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $role = Role::where('user_id', 2)->first();
        $response = $this->actingAs($user)->put('/roles/'.$role->id, [
            'user_id' => 2,
            'role_name' => 'admin'
        ]);
        // dd($response);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertDatabaseHas('roles', [
            'user_id' => 2,
            'role_name' => 'admin'
        ]);
        $this->assertTrue(true);
    }
    public function test_admin_can_delete_data_role()
    {
        $user = User::where('email', 'admnrt15balikpapan@gmail.com')->first();
        $role = Role::where('user_id', 2)->first();
        $response = $this->actingAs($user)->delete('/roles/'.$role->id);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
        $this->assertTrue(true);
        $this->assertDatabaseMissing('roles', ['user_id' => '2']);
    }
}
