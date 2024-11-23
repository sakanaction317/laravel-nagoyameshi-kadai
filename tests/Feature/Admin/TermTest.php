<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Models\Term;

class TermTest extends TestCase
{
    use RefreshDatabase; 
    public function test_guest_cannot_access_admin_terms_index() 
    { 
        $response = $this->get(route('admin.terms.index')); 
        $response->assertRedirect(route('admin.login'));
    }
    
    public function test_non_admin_user_cannot_access_admin_terms_index() 
    { 
        $user = User::factory()->create(); 
        $response = $this->actingAs($user)->get(route('admin.terms.index')); 
        $response->assertRedirect(route('admin.login')); 
    } 
    
    public function test_admin_user_can_access_admin_terms_index() 
    { 
        $admin = Admin::factory()->create(); 
        $term = Term::factory()->create(); 
        $response = $this->actingAs($admin, 'admin')->get(route('admin.terms.index')); 
        $response->assertOk(); 
        $response->assertViewHas('term', $term); 
    } 
    
    public function test_guest_cannot_access_admin_terms_edit() 
    { 
        $term = Term::factory()->create(); 
        $response = $this->get(route('admin.terms.edit', $term)); 
        $response->assertRedirect(route('admin.login')); 
    } 
    
    public function test_non_admin_user_cannot_access_admin_terms_edit() 
    { 
        $user = User::factory()->create(); 
        $term = Term::factory()->create(); 
        $response = $this->actingAs($user)->get(route('admin.terms.edit', $term)); 
        $response->assertRedirect(route('admin.login')); 
    } 
    
    public function test_admin_user_can_access_admin_terms_edit() 
    { 
        $admin = Admin::factory()->create(); 
        $term = Term::factory()->create(); 
        $response = $this->actingAs($admin, 'admin')->get(route('admin.terms.edit', $term)); 
        $response->assertOk(); 
        $response->assertViewHas('term', $term); 
    } 
    
    public function test_guest_cannot_update_term() 
    { 
        $term = Term::factory()->create(); 
        $response = $this->patch(route('admin.terms.update', $term), $term->toArray()); 
        $response->assertRedirect(route('admin.login')); 
    } 
    
    public function test_non_admin_user_cannot_update_term() 
    { 
        $user = User::factory()->create(); 
        $term = Term::factory()->create(); 
        $response = $this->actingAs($user)->patch(route('admin.terms.update', $term), $term->toArray()); 
        $response->assertRedirect(route('admin.login')); 
    } 
        
        public function test_admin_user_can_update_term() 
    { 
        $admin = Admin::factory()->create(); 
        $term = Term::factory()->create(); 
        $updateData = ['content' => '更新テスト']; 
        $response = $this->actingAs($admin, 'admin')->patch(route('admin.terms.update', $term), $updateData); 
        $response->assertRedirect(route('admin.terms.index')); 
        $this->assertDatabaseHas('terms', $updateData); 
    }
}
