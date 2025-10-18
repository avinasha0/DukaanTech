<?php

namespace App\Console\Commands;

use App\Models\TerminalSession;
use Illuminate\Console\Command;

class CleanupTerminalSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'terminal:sessions:cleanup 
                            {--force : Force cleanup of all old sessions}
                            {--hours=24 : Hours threshold for old sessions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired and orphaned terminal sessions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting terminal session cleanup...');

        if ($this->option('force')) {
            $this->warn('Force mode enabled - cleaning up all old sessions');
            $hours = $this->option('hours');
            $oldSessions = TerminalSession::cleanupOldSessions($hours);
            $this->info("Cleaned up {$oldSessions} sessions older than {$hours} hours");
        }

        // Run comprehensive cleanup
        $result = TerminalSession::comprehensiveCleanup();

        $this->info('Session cleanup completed:');
        $this->table(
            ['Type', 'Count'],
            [
                ['Expired Sessions', $result['expired_sessions']],
                ['Old Sessions', $result['old_sessions']],
                ['Orphaned Sessions', $result['orphaned_sessions']],
                ['Total Cleaned', $result['total_cleaned']]
            ]
        );

        if ($result['total_cleaned'] > 0) {
            $this->info("Successfully cleaned up {$result['total_cleaned']} terminal sessions");
        } else {
            $this->info('No sessions needed cleanup');
        }

        return Command::SUCCESS;
    }
}
