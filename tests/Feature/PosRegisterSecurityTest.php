<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Account;
use App\Models\TerminalUser;
use App\Models\TerminalSession;

class PosRegisterSecurityTest extends TestCase
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

    public function test_pos_register_redirects_to_terminal_login_when_not_authenticated()
    {
        $response = $this->get("/{$this->tenant->slug}/pos/register");
        
        $response->assertRedirect(route('terminal.login', ['tenant' => $this->tenant->slug]));
        $response->assertSessionHas('error', 'Please login to access the POS terminal');
    }

    public function test_pos_register_allows_access_with_terminal_authentication()
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

        // Create a session
        $session = $terminalUser->createSession();

        // Make request with session cookie
        $response = $this->withCookie('terminal_session_token', $session->session_token)
            ->get("/{$this->tenant->slug}/pos/register");

        $response->assertStatus(200);
        $response->assertSee('POS Terminal');
    }

    public function test_pos_register_allows_access_with_regular_authentication()
    {
        // Create a regular user and authenticate
        $user = \App\Models\User::factory()->create([
            'tenant_id' => $this->tenant->id,
        ]);

        $response = $this->actingAs($user)
            ->get("/{$this->tenant->slug}/pos/register");

        $response->assertStatus(200);
        $response->assertSee('POS Terminal');
    }

    public function test_pos_register_denies_access_with_expired_terminal_session()
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

        // Create an expired session
        $session = TerminalSession::create([
            'terminal_user_id' => $terminalUser->id,
            'session_token' => 'expired-token',
            'expires_at' => now()->subHour(), // Expired 1 hour ago
            'last_activity_at' => now()->subHour(),
        ]);

        // Make request with expired session cookie
        $response = $this->withCookie('terminal_session_token', 'expired-token')
            ->get("/{$this->tenant->slug}/pos/register");

        $response->assertRedirect(route('terminal.login', ['tenant' => $this->tenant->slug]));
        $response->assertSessionHas('error', 'Please login to access the POS terminal');
    }
}