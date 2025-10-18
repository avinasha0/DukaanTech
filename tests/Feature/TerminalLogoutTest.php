<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Account;
use App\Models\TerminalUser;
use App\Models\TerminalSession;

class TerminalLogoutTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test tenant
        $this->tenant = Account::create([
            'name' => 'Test Restaurant',
            'slug' => 'test-restaurant',
            'plan' => 'free',
            'kot_enabled' => true,
        ]);
    }

    public function test_logout_without_session_token_returns_success()
    {
        $response = $this->post("/{$this->tenant->slug}/terminal/api/logout");
        
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function test_logout_with_valid_session_token_deletes_session()
    {
        // Create a terminal user and session
        $terminalUser = TerminalUser::create([
            'tenant_id' => $this->tenant->id,
            'terminal_id' => 'TEST01',
            'name' => 'Test User',
            'pin' => '1234',
            'role' => 'cashier',
            'is_active' => true,
        ]);

        $session = $terminalUser->createSession();

        $response = $this->post("/{$this->tenant->slug}/terminal/api/logout", [], [
            'X-Terminal-Session-Token' => $session->session_token,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);

        // Check that the session was deleted
        $this->assertDatabaseMissing('terminal_sessions', [
            'id' => $session->id,
        ]);
    }

    public function test_logout_with_invalid_session_token_returns_success()
    {
        $response = $this->post("/{$this->tenant->slug}/terminal/api/logout", [], [
            'X-Terminal-Session-Token' => 'invalid-token',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function test_logout_returns_success_response()
    {
        $response = $this->post("/{$this->tenant->slug}/terminal/api/logout");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}