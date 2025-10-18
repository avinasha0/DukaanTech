<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_isolation_prevents_cross_tenant_access()
    {
        // Create two tenants
        $tenant1 = Account::create([
            'name' => 'Restaurant 1',
            'slug' => 'restaurant1',
            'settings' => []
        ]);

        $tenant2 = Account::create([
            'name' => 'Restaurant 2', 
            'slug' => 'restaurant2',
            'settings' => []
        ]);

        // Create categories for each tenant
        $category1 = Category::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Tenant 1 Category',
            'position' => 1
        ]);

        $category2 = Category::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Tenant 2 Category',
            'position' => 1
        ]);

        // Create users for each tenant
        $user1 = User::create([
            'name' => 'User 1',
            'email' => 'user1@tenant1.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant1->id
        ]);

        $user2 = User::create([
            'name' => 'User 2',
            'email' => 'user2@tenant2.com', 
            'password' => bcrypt('password'),
            'tenant_id' => $tenant2->id
        ]);

        // Test that tenant1 can only see their own categories
        $this->actingAs($user1);
        app()->instance('tenant.id', $tenant1->id);
        
        $categories = Category::all();
        $this->assertCount(1, $categories);
        $this->assertEquals($category1->id, $categories->first()->id);

        // Test that tenant2 can only see their own categories
        $this->actingAs($user2);
        app()->instance('tenant.id', $tenant2->id);
        
        $categories = Category::all();
        $this->assertCount(1, $categories);
        $this->assertEquals($category2->id, $categories->first()->id);
    }

    public function test_tenant_scoped_trait_automatically_adds_tenant_id()
    {
        $tenant = Account::create([
            'name' => 'Test Restaurant',
            'slug' => 'test',
            'settings' => []
        ]);

        app()->instance('tenant.id', $tenant->id);

        $category = Category::create([
            'name' => 'Test Category',
            'position' => 1
        ]);

        $this->assertEquals($tenant->id, $category->tenant_id);
    }
}
