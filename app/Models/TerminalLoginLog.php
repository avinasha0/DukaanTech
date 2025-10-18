<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TerminalLoginLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'terminal_user_id',
        'device_id',
        'session_token',
        'action',
        'ip_address',
        'user_agent',
        'logged_at',
        'metadata',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'tenant_id');
    }

    public function terminalUser(): BelongsTo
    {
        return $this->belongsTo(TerminalUser::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Log a terminal user action
     */
    public static function logAction(
        int $tenantId,
        int $terminalUserId,
        string $action,
        ?int $deviceId = null,
        ?string $sessionToken = null,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        ?array $metadata = null
    ): self {
        return self::create([
            'tenant_id' => $tenantId,
            'terminal_user_id' => $terminalUserId,
            'device_id' => $deviceId,
            'session_token' => $sessionToken,
            'action' => $action,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'logged_at' => now(),
            'metadata' => $metadata,
        ]);
    }

    /**
     * Get login logs for a terminal user
     */
    public static function getLoginHistory(int $terminalUserId, int $days = 30): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('terminal_user_id', $terminalUserId)
            ->where('logged_at', '>=', now()->subDays($days))
            ->orderBy('logged_at', 'desc')
            ->with(['device', 'terminalUser'])
            ->get();
    }

    /**
     * Get active sessions count for a terminal user
     */
    public static function getActiveSessionsCount(int $terminalUserId): int
    {
        return self::where('terminal_user_id', $terminalUserId)
            ->where('action', 'login')
            ->where('logged_at', '>=', now()->subHours(8)) // Assuming 8-hour session max
            ->whereDoesntHave('terminalUser.sessions', function ($query) {
                $query->where('expires_at', '>', now());
            })
            ->count();
    }

    /**
     * Scope for login actions
     */
    public function scopeLogins($query)
    {
        return $query->where('action', 'login');
    }

    /**
     * Scope for logout actions
     */
    public function scopeLogouts($query)
    {
        return $query->whereIn('action', ['logout', 'session_expired']);
    }

    /**
     * Scope for recent logs
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('logged_at', '>=', now()->subHours($hours));
    }
}
