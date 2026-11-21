<?php

namespace App\Console\Commands;

use App\Services\JobScraperService;
use Illuminate\Console\Command;

class ExpireJobsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically remove expired jobs whose application deadline has passed';

    /**
     * Execute the console command.
     */
    public function handle(JobScraperService $scraper): int
    {
        $count = $scraper->purgeExpiredJobs();

        $this->info("Expired jobs auto-removal completed. {$count} outdated circulars permanently deleted from database.");

        return self::SUCCESS;
    }
}
