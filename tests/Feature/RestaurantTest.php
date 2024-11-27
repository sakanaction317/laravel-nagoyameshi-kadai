<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_store_page()
    {
        $response = $this->get(route('restaurants.index'));
        $response->assertStatus(200);
    }

    public function test_user_can_access_store_page()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('restaurants.index'));
        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_store_page()
    {
        $admin = Admin::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('restaurants.index'));
        $response->assertStatus(302);
        $response->assertRedirect('admin/home');
    }
}
