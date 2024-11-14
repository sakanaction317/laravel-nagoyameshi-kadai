<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 未ログインユーザーは管理者側の会員一覧ページにアクセスできない
     */
    public function test_guest_cannot_access_admin_user_index():void
    {
        $response = $this->get('/admin/users');

        $response->assertRedirect('/admin/login');
    }

    /**
     * ログイン済のユーザーは管理者側の会員一覧ページにアクセスできない
     */
    public function test_authenticated_users_cannot_access_admin_user_index():void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/admin/users');

        $response->assertStatus(302);
    }

    /**
     * ログイン済の管理者は管理者側の会員一覧ページにアクセスできる
     */
    public function test_admins_can_access_admin_user_index():void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin');

        $response = $this->get('/admin/users');

        $response->assertStatus(200);
    }

    /**
     * 未ログインユーザーは管理者側の会員詳細ページにアクセスできない
     */
    public function test_guests_cannot_access_admin_user_show():void
    {
        $user = User::factory()->create();

        $response = $this->get('/admin/users/' . $user->id);

        $response->assertRedirect('/admin/login');
    }

    /**
     * ログイン済のユーザーは管理者側の会員詳細ページにアクセスできない
     */
    public function test_authenticated_users_cannot_access_admin_user_show():void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/admin/users/' . $otherUser->id);

        $response->assertStatus(302);
    }

    /**
     * ログイン済の管理者は管理者側の会員詳細ページにアクセスできる
     */
    public function test_admins_can_access_admin_user_show():void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin');
        $user = User::factory()->create();

        $response = $this->get('/admin/users/' . $user->id);

        $response->assertStatus(200);
    }
}
