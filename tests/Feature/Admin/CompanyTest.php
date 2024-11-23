<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Company;
use App\Models\User;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_company_index()
    {
        $response = $this->get(route('admin.company.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_user_cannot_access_company_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.company.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_can_access_admin_company_index()
    {
        $admin = Admin::factory()->create();
        $company = Company::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.company.index'));
        $response->assertOk();
    }

    public function test_guets_cannot_access_admin_company_edit()
    {
        $company = Company::factory()->create();
        $response = $this->get(route('admin.company.edit', $company));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_not_admin_user_cannot_access_admin_company_index()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.company.edit', $company));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_can_access_admin_company_edit()
    {
        $admin = Admin::factory()->create();
        $company = Company::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.company.edit', $company));
        $response->assertOk();
    }

    public function test_guest_cannot_update_company()
    {
        $company = Company::factory()->create();
        $response = $this->patch(route('admin.company.update', $company), $company->toArray());
        $response->assertRedirect(route('admin.login'));
    }

    public function test_not_admin_user_cannot_access_update_company()
    {
        $company = Company::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch(route('admin.company.update', $company), $company->toArray());
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_user_can_update_company()
    {
        $admin = Admin::factory()->create();
        $company = Company::factory()->create();
        $updateData = [
            'name' => 'テスト', 
            'postal_code' => '1234567', 
            'address' => '住所', 
            'representative' => '代表者', 
            'establishment_date' => '年月日', 
            'capital' => '資本金', 
            'business' => '事業内容', 
            'number_of_employees' => '従業員数',
        ];
        $response = $this->actingAs($admin, 'admin')->patch(route('admin.company.update', $company), $updateData);
        $response->assertRedirect(route('admin.company.index'));
        $this->assertDatabaseHas('companies', $updateData);
    }
}

