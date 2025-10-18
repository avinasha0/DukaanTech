<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\TerminalLoginLog;

class TerminalSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'terminal_user_id',
        'device_id',
        'session_token',
        'expires_at',
        'last_activity_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_activity_at' => 'datetime',
    ];

    public function terminalUser(): BelongsTo
    {
        return $this->belongsTo(TerminalUser::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Check if session is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if session is valid (not expired)
     */
    public function isValid(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Update last activity timestamp
     */
    public function updateActivity(): void
    {
        $this->update(['last_activity_at' => now()]);
    }

    /**
     * Extend session expiration
     */
    public function extend($hours = 8): void
    {
        $this->update([
            'expires_at' => now()->addHours($hours),
            'last_activity_at' => now(),
        ]);
    }

    /**
     * Scope for active sessions
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope for expired sessions
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Clean up all expired sessions
     */
    public static function cleanupExpired(): int
    {
        // Log session expirations before deleting
        $expiredSessions = self::expired()->with(['terminalUser', 'device'])->get();
        foreach ($expiredSessions as $session) {
            if ($session->terminalUser) {
                TerminalLoginLog::logAction(
                    tenantId: $session->terminalUser->tenant_id,
                    terminalUserId: $session->terminalUser->id,
                    action: 'session_expired',
                    deviceId: $session->device_id,
                    sessionToken: $session->session_token,
                    metadata: [
                        'device_name' => $session->device?->name,
                        'session_duration' => $session->created_at->diffInMinutes(now()) . ' minutes',
                        'expired_at' => $session->expires_at->toISOString()
                    ]
                );
            }
        }
        
        return self::expired()->delete();
    }

    /**
     * Clean up all sessions older than specified hours (for force cleanup)
     */
    public static function cleanupOldSessions($hours = 24): int
    {
        return self::where('last_activity_at', '<', now()->subHours($hours))->delete();
    }

    /**
     * Clean up orphaned sessions (sessions without valid terminal users)
     */
    public static function cleanupOrphanedSessions(): int
    {
        return self::whereDoesntHave('terminalUser')->delete();
    }

    /**
     * Comprehensive session cleanup
     */
    public static function comprehensiveCleanup(): array
    {
        $expired = self::cleanupExpired();
        $old = self::cleanupOldSessions(24);
        $orphaned = self::cleanupOrphanedSessions();
        
        \Log::info("Comprehensive session cleanup completed", [
            'expired_sessions' => $expired,
            'old_sessions' => $old,
            'orphaned_sessions' => $orphaned
        ]);
        
        return [
            'expired_sessions' => $expired,
            'old_sessions' => $old,
            'orphaned_sessions' => $orphaned,
            'total_cleaned' => $expired + $old + $orphaned
        ];
    }
}