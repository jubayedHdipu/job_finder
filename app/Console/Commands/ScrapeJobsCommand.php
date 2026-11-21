<?php

namespace App\Console\Commands;

use App\Services\JobScraperService;
use Illuminate\Console\Command;

class ScrapeJobsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:scrape {--source= : Optional source filter: Bdjobs, Teletalk, Skill.jobs, LinkedIn}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape job listings with URL health validation, expired deadline filtering, and duplicate prevention';

    /**
     * Execute the console command.
     */
    public function handle(JobScraperService $scraper): int
    {
        $this->info('Starting BD JobFinder Scraper Engine (Validating Links & Deadlines)...');
        $source = $this->option('source');

        if ($source) {
            $sourceLower = strtolower($source);
            $results = match ($sourceLower) {
                'bdjobs' => ['Bdjobs' => $scraper->scrapeBdjobs()],
                'teletalk' => ['Teletalk' => $scraper->scrapeTeletalkGovt()],
                'skill.jobs', 'skilljobs' => ['Skill.jobs' => $scraper->scrapeSkillJobs()],
                'linkedin' => ['LinkedIn' => $scraper->scrapeLinkedIn()],
                default => null,
            };

            if ($results === null) {
                $this->error("Unknown source: {$source}. Allowed: Bdjobs, Teletalk, Skill.jobs, LinkedIn");

                return self::FAILURE;
            }
        } else {
            $results = $scraper->scrapeAll();
        }

        $totalAdded = 0;
        $totalSkipped = 0;
        $totalInvalid = 0;
        $totalExpired = 0;

        $rows = [];
        foreach ($results as $src => $data) {
            $totalAdded += $data['added'];
            $totalSkipped += $data['skipped'];
            $totalInvalid += $data['invalid'] ?? 0;
            $totalExpired += $data['expired'] ?? 0;

            $rows[] = [
                $src,
                $data['added'],
                $data['skipped'],
                $data['invalid'] ?? 0,
                $data['expired'] ?? 0,
                $data['status'],
                $data['message'],
            ];
        }

        $this->table(['Source', 'Added', 'Duplicates Skipped', 'Broken Links Rejected', 'Expired Skipped', 'Status', 'Message'], $rows);
        $this->info("Completed! Total Added: {$totalAdded} | Duplicates: {$totalSkipped} | Broken URLs Rejected: {$totalInvalid} | Expired Skipped: {$totalExpired}");

        return self::SUCCESS;
    }
}
