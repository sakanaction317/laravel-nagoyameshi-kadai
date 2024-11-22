<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Admin;
use App\Models\Category;
use App\Models\RegularHoliday;

class RestaurantTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // indexアクションのテスト
    public function test_guest_cannot_access_admin_restaurant_index()
    {
        $response = $this->get(route('admin.restaurants.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_not_admin_user_cannot_access_admin_restaurant_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_can_access_admin_restaurants_index()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.index'));
        $response->assertOk();
    }

    // showアクションのテスト
    public function test_guest_cannot_access_admin_restaurant_show()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.show', $restaurant));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_not_admin_user_cannot_access_admin_restaurant_show()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.show', $restaurant));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_can_access_admin_restaurants_show()
    {
        $admin = Admin::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.show', $restaurant));
        $response->assertOk();
    }

    // createアクションのテスト
    public function test_guest_cannot_access_admin_restaurant_create()
    {
        $response = $this->get(route('admin.restaurants.create'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_not_admin_user_cannot_access_admin_restaurant_create()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.create'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_can_access_admin_restaurants_create()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.create'));
        $response->assertOk();
    }

    // storeアクションのテスト
    public function test_guest_cannot_store_admin_restaurant()
    {
        $response = $this->post(route('admin.restaurants.store'), [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00',
            'closing_time' => '20:00',
            'seating_capacity' => 50,
        ]);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_not_admin_user_cannot_store_admin_restaurant()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.restaurants.store'), [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00',
            'closing_time' => '20:00',
            'seating_capacity' => 50,
        ]);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_can_store_admin_restaurant()
    {
        $admin = Admin::factory()->create();
        $categories = Category::factory()->count(3)->create();
        $category_ids = $categories->pluck('id')->toArray();
        $regular_holidays = RegularHoliday::factory()->count(2)->create();
        $regular_holiday_ids = $regular_holidays->pluck('id')->toArray();

        $restaurant_data = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00',
            'closing_time' => '20:00',
            'seating_capacity' => 50,
            'category_ids' => $category_ids,
            'regular_holiday_ids' => $regular_holiday_ids,
        ];

        $response = $this->actingAs($admin, 'admin')->post(route('admin.restaurants.store'), [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00',
            'closing_time' => '20:00',
            'seating_capacity' => 50,
            'category_ids' => $category_ids,
            'regular_holiday_ids' => $regular_holiday_ids,
        ]);

        unset($restaurant_data['category_ids']);
        unset($restaurant_data['regular_holiday_ids']);

        $response->assertRedirect(route('admin.restaurants.index'));
        $this->assertDatabaseHas('restaurants', [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00',
            'closing_time' => '20:00',
            'seating_capacity' => 50,
        ]);

        foreach ($category_ids as $category_id) {
            $this->assertDatabaseHas('category_restaurant', [
                'restaurant_id' => Restaurant::latest('id')->first()->id,
                'category_id' => $category_id
            ]);
        }

        foreach ($regular_holiday_ids as $regular_holiday_id) {
            $this->assertDatabaseHas('regular_holiday_restaurant', [
                'restaurant_id' => Restaurant::latest('id')->first()->id,
                'regular_holiday_id' => $regular_holiday_id,
            ]);
        }
    }
    
    // editアクションのテスト
    public function test_guest_cannot_access_admin_restaurant_edit()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.edit', $restaurant));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_not_admin_user_cannot_access_admin_restaurant_edit()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.edit', $restaurant));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_can_access_admin_restaurants_edit()
    {
        $admin = Admin::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.restaurants.edit', $restaurant));
        $response->assertOk();
    }

    // updateアクションのテスト
    public function test_guest_cannot_update_restaurant()
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->patch(route('admin.restaurants.update', $restaurant),[
            'name' => '更新テスト',
            'description' => '更新テスト',
            'lowest_price' => 2000,
            'highest_price' => 6000,
            'postal_code' => '1111111',
            'address' => '更新テスト',
            'opening_time' => '09:00',
            'closing_time' => '21:00',
            'seating_capacity' => 60,
        ]);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_not_admin_user_cannot_update_restaurant()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->patch(route('admin.restaurants.update', $restaurant), [
            'name' => '更新テスト',
            'description' => '更新テスト',
            'lowest_price' => 2000,
            'highest_price' => 6000,
            'postal_code' => '1111111',
            'address' => '更新テスト',
            'opening_time' => '09:00',
            'closing_time' => '21:00',
            'seating_capacity' => 60,
        ]);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_can_update_restaurant()
    {
        $admin = Admin::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $categories = Category::factory()->count(3)->create();
        $category_ids = $categories->pluck('id')->toArray();
        $regular_holidays = RegularHoliday::factory()->count(2)->create();
        $regular_holiday_ids = $regular_holidays->pluck('id')->toArray();

        $new_restaurant_data = [
            'name' => '更新テスト',
            'description' => '更新テスト',
            'lowest_price' => 2000,
            'highest_price' => 6000,
            'postal_code' => '1111111',
            'address' => '更新テスト',
            'opening_time' => '09:00',
            'closing_time' => '21:00',
            'seating_capacity' => 60,
            'category_ids' => $category_ids,
            'regular_holiday_ids' => $regular_holiday_ids,
        ];

        $response = $this->actingAs($admin, 'admin')->patch(route('admin.restaurants.update', $restaurant),  [
            'name' => '更新テスト',
            'description' => '更新テスト',
            'lowest_price' => 2000,
            'highest_price' => 6000,
            'postal_code' => '1111111',
            'address' => '更新テスト',
            'opening_time' => '09:00',
            'closing_time' => '21:00',
            'seating_capacity' => 60,
            'category_ids' => $category_ids,
            'regular_holiday_ids' => $regular_holiday_ids,
        ]);

        unset($new_restaurant_data['category_ids']);
        unset($new_restaurant_data['regular_holiday_ids']);
        
        $response->assertRedirect(route('admin.restaurants.show', $restaurant));
        $this->assertDatabaseHas('restaurants', [
            'name' => '更新テスト',
            'description' => '更新テスト',
            'lowest_price' => 2000,
            'highest_price' => 6000,
            'postal_code' => '1111111',
            'address' => '更新テスト',
            'opening_time' => '09:00',
            'closing_time' => '21:00',
            'seating_capacity' => 60,
        ]);

        foreach ($category_ids as $category_id) {
            $this->assertDatabaseHas('category_restaurant', [
                'restaurant_id' => $restaurant->id,
                'category_id' => $category_id
            ]);
        }

        foreach ($regular_holiday_ids as $regular_holiday_id) {
            $this->assertDatabaseHas('regular_holiday_restaurant', [
                'restaurant_id' => $restaurant->id,
                'regular_holiday_id' => $regular_holiday_id,
            ]);
        }
    }

    // destroyアクションのテスト
    public function test_guest_cannot_delete_restaurant()
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->delete(route('admin.restaurants.destroy', $restaurant));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_not_admin_user_cannot_delete_restaurant()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.restaurants.destroy', $restaurant));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_can_delete_restaurant()
    {
        $admin = Admin::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($admin, 'admin')->delete(route('admin.restaurants.destroy', $restaurant));
        $response->assertRedirect(route('admin.restaurants.index'));
        $this->assertDatabaseMissing('restaurants', [
            'id' => $restaurant->id,
        ]);
    }
}
