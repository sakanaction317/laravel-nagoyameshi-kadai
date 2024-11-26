<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    public function test_guest_cannot_access_user_info_page()
    {
        $response = $this->get('/user');
        $response->assertRedirect('/login');
    }

    public function test_user_can_access_user_info_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/user');
        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_user_info_page()
    {
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin)->get('/user');
        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_user_edit_page()
    {
        $response = $this->get('/user/1/edit');
        $response->assertRedirect('login');
    }

    public function test_login_user_cannot_access_other_edit_page()
    {
        $user = User::factory()->create();
        $otheruser = User::factory()->create();
        $response = $this->actingAs($user)->get('/user/{$otheruser->id}/edit');
        $response->assertStatus(403);
    }

    public function test_login_user_can_access_edit_page()
    {
        $user = User::factory()->create();
        $response = $this->actindAs($user)->get('/user/{$user->id}/edit');
        $response->assertStatus(200);
    }

    public function test_admin_cannot_user_edit_page()
    {
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin);
        $response->assertStatus(403);
    }

    public function test_guest_cannot_update_user_info()
    {
        $response = $this->patch('/user/1', []);
        $response->assertRedirect('/login');
    }

    public function test_user_cannot_update_other_user_info()
    {
        $user = User::factory()->create();
        $otheruser = User::factory()->create();
        $response = $this->actingAs($user)->patch("/user/{$otheruser->id}", []);
        $response->assertStatus(403);
    }

    public function test_user_can_update_user_info()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch("/user/{$user->id}", [
            'name' => 'update name',
        ]);
        $response->assertRedirect("/user/{$user->id}/edit");
    }

    public function test_admin_cannot_update_user_info()
    {
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin)->patch('/user/1', []);
        $response->assertStatus(403);
    }
}
