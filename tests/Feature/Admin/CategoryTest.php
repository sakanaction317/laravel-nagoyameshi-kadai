<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // カテゴリ一覧ページのテスト
    public function test_guest_cannot_access_admin_category_index()
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_not_admin_user_cannot_access_admin_category_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.categories.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_can_access_admin_categories_index()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.categories.index'));
        $response->assertOk();
    }

    // カテゴリ機能のテスト
    public function test_guest_cannot_access_store_category()
    {
        $response = $this->post(route('admin.categories.store'), [
            'name' => 'カテゴリ',
        ]);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_not_admin_user_cannot_store_category()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.categories.store'), [
            'name' => 'カテゴリ',
        ]);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_store_category()
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.categories.store'), [
            'name' => 'カテゴリ',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'カテゴリ']);
    }

    // カテゴリ更新機能のテスト
    public function test_guest_cannot_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->patch(route('admin.categories.update', $category), [
            'name' => 'カテゴリ更新',
        ]);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_not_admin_user_update_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->patch(route('admin.categories.update', $category), [
            'name' => 'カテゴリ更新',
        ]);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_can_update_category()
    {
        $admin = Admin::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin, 'admin')->patch(route('admin.categories.update', $category), [
            'name' => 'カテゴリ更新',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'カテゴリ更新']);
    }

    // カテゴリ削除機能
    public function test_guest_cannot_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_not_admin_user_cannot_delete_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.categories.destroy', ['category' => $category->id]));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_can_delete_category()
    {
        $admin = Admin::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin, 'admin')->delete(route('admin.categories.destroy', $category));
        $response->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
