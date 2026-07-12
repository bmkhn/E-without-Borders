<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class PruneActivityLogs extends Command
{
    protected $signature = 'activitylog:prune {--dry-run : Show how many records would be deleted without actually deleting}';
    protected $description = 'Delete activity log entries older than 1 year';

    public function handle(): int
    {
        $cutoff = now()->subYear();

        $count = Activity::query()
            ->where('created_at', '<', $cutoff)
            ->count();

        if ($count === 0) {
            $this->info('No activity log entries older than 1 year found.');
            return Command::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->line("{$count} activity log entr" . ($count === 1 ? 'y' : 'ies') . " older than 1 year would be deleted.");
            $this->line('Run without --dry-run to perform the deletion.');
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        // Chunk to avoid memory issues with large tables
        Activity::query()
            ->where('created_at', '<', $cutoff)
            ->chunkById(100, function ($logs) use ($bar) {
                foreach ($logs as $log) {
                    $log->delete();
                    $bar->advance();
                }
            });

        $bar->finish();
        $this->newLine();
        $this->info("Deleted {$count} activity log entr" . ($count === 1 ? 'y' : 'ies') . " older than 1 year.");

        return Command::SUCCESS;
    }
}
