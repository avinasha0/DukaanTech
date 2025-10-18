<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Account;
use App\Models\TerminalUser;
use App\Models\TerminalSession;

class TerminalAuthTest extends TestCase
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

    public function test_terminal_login_page_loads()
    {
        $response = $this->get("/{$this->tenant->slug}/terminal/login");
        
        $response->assertStatus(200);
        $response->assertSee('Terminal Login');
        $response->assertSee('Terminal ID');
        $response->assertSee('PIN');
    }

    public function test_terminal_login_with_valid_credentials()
    {
        // Create a terminal user
        $terminalUser = TerminalUser::create([
            'tenant_id' => $this->tenant->id,
            'terminal_id' => 'TEST01',
            'name' => 'Test User',
            'pin' => '1234',
            'role' => 'cashier',
            'is_active' => true,
        ]);

        $response = $this->post("/{$this->tenant->slug}/terminal/login", [
            'terminal_id' => 'TEST01',
            'pin' => '1234',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Login successful',
        ]);

        // Check that a session was created
        $this->assertDatabaseHas('terminal_sessions', [
            'terminal_user_id' => $terminalUser->id,
        ]);
    }

    public function test_terminal_login_with_invalid_credentials()
    {
        $response = $this->post("/{$this->tenant->slug}/terminal/login", [
            'terminal_id' => 'INVALID',
            'pin' => '0000',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['terminal_id']);
    }

    public function test_terminal_login_with_inactive_user()
    {
        // Create an inactive terminal user
        $terminalUser = TerminalUser::create([
            'tenant_id' => $this->tenant->id,
            'terminal_id' => 'INACTIVE01',
            'name' => 'Inactive User',
            'pin' => '1234',
            'role' => 'cashier',
            'is_active' => false,
        ]);

        $response = $this->post("/{$this->tenant->slug}/terminal/login", [
            'terminal_id' => 'INACTIVE01',
            'pin' => '1234',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['terminal_id']);
    }

    public function test_terminal_logout()
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
            'message' => 'Logged out successfully',
        ]);

        // Check that the session was deleted
        $this->assertDatabaseMissing('terminal_sessions', [
            'id' => $session->id,
        ]);
    }

    public function test_terminal_session_validation()
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

        $response = $this->get("/{$this->tenant->slug}/terminal/api/session", [
            'X-Terminal-Session-Token' => $session->session_token,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'user' => [
                'terminal_id' => 'TEST01',
                'name' => 'Test User',
                'role' => 'cashier',
            ],
        ]);
    }

    public function test_terminal_session_with_invalid_token()
    {
        $response = $this->get("/{$this->tenant->slug}/terminal/api/session", [
            'X-Terminal-Session-Token' => 'invalid-token',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'error' => 'Invalid or expired session',
        ]);
    }
}