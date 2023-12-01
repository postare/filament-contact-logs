<?php

namespace Postare\FilamentContactLogs\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Postare\FilamentContactLogs\Models\ContactLog;

class FilamentContactLogsCommand extends Command
{
    public $signature = 'contact-logs:clean
                         {--days= : (optional) Records older than this number of days will be cleaned.}';

    public $description = 'Clean up old records from the contact log.';

    public function handle(): int
    {
        $this->comment('Cleaning activity log...');

        $maxAgeInDays = $this->option('days') ?? config('contact-logs.delete_records_older_than_days');

        $cutOffDate = Carbon::now()->subDays($maxAgeInDays)->format('Y-m-d H:i:s');

        $contactLogModel = config('contact-logs.contact_log_model') ?? ContactLog::class;

        $amountDeleted = $contactLogModel::query()
            ->where('created_at', '<', $cutOffDate)
            ->delete();

        $this->info("Deleted {$amountDeleted} record(s) from the activity log.");

        $this->comment('All done!');

        return 0;
    }
}
