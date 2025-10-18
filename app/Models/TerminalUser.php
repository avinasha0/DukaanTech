<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class TerminalUser extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'terminal_id',
        'name',
        'pin',
        'role',
        'is_active',
        'last_login_at',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    protected $hidden = [
        'pin',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'tenant_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(TerminalSession::class);
    }

    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class, 'terminal_user_id');
    }

    /**
     * Set the PIN attribute (hash it automatically)
     */
    public function setPinAttribute($value)
    {
        $this->attributes['pin'] = Hash::make($value);
    }

    /**
     * Verify a PIN against the stored hash
     */
    public function verifyPin($pin): bool
    {
        return Hash::check($pin, $this->pin);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Get active sessions for this user
     */
    public function getActiveSessions()
    {
        return $this->sessions()
            ->where('expires_at', '>', now())
            ->orderBy('last_activity_at', 'desc')
            ->get();
    }

    /**
     * Create a new session for this user
     */
    public function createSession($deviceId = null, $duration = 8): TerminalSession
    {
        // Clean up expired sessions
        $this->sessions()->where('expires_at', '<=', now())->delete();

        $session = $this->sessions()->create([
            'device_id' => $deviceId,
            'session_token' => bin2hex(random_bytes(32)),
            'expires_at' => now()->addHours($duration),
            'last_activity_at' => now(),
        ]);

        return $session;
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Check if user has any active sessions
     */
    public function hasActiveSession(): bool
    {
        return $this->sessions()
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Clean up expired sessions for this user
     */
    public function cleanupExpiredSessions(): int
    {
        return $this->sessions()
            ->where('expires_at', '<=', now())
            ->delete();
    }
}