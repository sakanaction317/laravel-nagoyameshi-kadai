<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;

class HomeTest extends TestCase
{
   use RefreshDatabase;

   public function test_guest_can_access_home_page()
   {
    $response = $this->get(route('home'));
    $response->assertStatus(200);
   }

   public function test_login_user_can_access_home_page()
   {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('home'));
    $response->assertStatus(200);
   }

   public function test_login_user_admin_cannot_home_page()
   {
    $admin = Admin::factory()->create();
    $response = $this->actingAs($admin, 'admin')->get(route('home'));
    $response->assertRedirect(route('admin.home'));
   }
}
