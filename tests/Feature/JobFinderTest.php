<?php

namespace Tests\Feature;

use App\Models\ScrapedJob;
use App\Models\User;
use App\Services\JobScraperService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class JobFinderTest extends TestCase
{
    use RefreshDatabase;

    public function test_front_page_displays_ongoing_jobs(): void
    {
        $activeJob = ScrapedJob::create([
            'title' => 'Software Engineer',
            'slug' => 'software-engineer',
            'company_name' => 'Tech Corp',
            'location' => 'Dhaka',
            'category' => 'private',
            'experience_level' => 'fresher',
            'short_description' => 'Test description',
            'source_name' => 'Bdjobs',
            'source_url' => 'https://example.com/job1',
            'job_hash' => ScrapedJob::generateHash('Software Engineer', 'Tech Corp', now()->addDays(10)->format('Y-m-d')),
            'deadline' => now()->addDays(10)->format('Y-m-d'),
            'is_active' => true,
        ]);

        $expiredJob = ScrapedJob::create([
            'title' => 'Past Manager',
            'slug' => 'past-manager',
            'company_name' => 'Old Corp',
            'location' => 'Chattogram',
            'category' => 'private',
            'experience_level' => 'senior',
            'short_description' => 'Old description',
            'source_name' => 'Bdjobs',
            'source_url' => 'https://example.com/job2',
            'job_hash' => ScrapedJob::generateHash('Past Manager', 'Old Corp', now()->subDays(2)->format('Y-m-d')),
            'deadline' => now()->subDays(2)->format('Y-m-d'),
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Software Engineer');
        $response->assertDontSee('Past Manager');

        // Verify expired job was automatically purged from database
        $this->assertDatabaseMissing('scraped_jobs', ['id' => $expiredJob->id]);
    }

    public function test_front_page_filters_by_category_and_location(): void
    {
        ScrapedJob::create([
            'title' => 'Govt Officer Post',
            'slug' => 'govt-officer-post',
            'company_name' => 'Ministry of Finance',
            'location' => 'Dhaka',
            'category' => 'govt',
            'experience_level' => 'fresher',
            'short_description' => 'Govt description',
            'source_name' => 'Teletalk',
            'source_url' => 'https://example.com/govt1',
            'job_hash' => ScrapedJob::generateHash('Govt Officer Post', 'Ministry of Finance', now()->addDays(15)->format('Y-m-d')),
            'deadline' => now()->addDays(15)->format('Y-m-d'),
            'is_active' => true,
        ]);

        ScrapedJob::create([
            'title' => 'Remote Python Dev',
            'slug' => 'remote-python-dev',
            'company_name' => 'Global Studio',
            'location' => 'Remote',
            'category' => 'remote',
            'experience_level' => 'mid',
            'short_description' => 'Remote description',
            'source_name' => 'LinkedIn',
            'source_url' => 'https://example.com/remote1',
            'job_hash' => ScrapedJob::generateHash('Remote Python Dev', 'Global Studio', now()->addDays(15)->format('Y-m-d')),
            'deadline' => now()->addDays(15)->format('Y-m-d'),
            'is_active' => true,
        ]);

        $response = $this->get('/?category=govt');
        $response->assertStatus(200);
        $response->assertSee('Govt Officer Post');
        $response->assertDontSee('Remote Python Dev');

        $responseRemote = $this->get('/?category=remote');
        $responseRemote->assertStatus(200);
        $responseRemote->assertSee('Remote Python Dev');
        $responseRemote->assertDontSee('Govt Officer Post');
    }

    public function test_job_details_page_loads_and_shows_related_jobs(): void
    {
        $job = ScrapedJob::create([
            'title' => 'Cyber Security Analyst',
            'slug' => 'cyber-security-analyst',
            'company_name' => 'Security BD',
            'location' => 'Dhaka',
            'category' => 'private',
            'experience_level' => 'mid',
            'short_description' => 'Monitor SOC operations.',
            'source_name' => 'Bdjobs',
            'source_url' => 'https://example.com/cyber1',
            'job_hash' => ScrapedJob::generateHash('Cyber Security Analyst', 'Security BD', now()->addDays(20)->format('Y-m-d')),
            'deadline' => now()->addDays(20)->format('Y-m-d'),
            'is_active' => true,
        ]);

        $relatedJob = ScrapedJob::create([
            'title' => 'Network Admin',
            'slug' => 'network-admin',
            'company_name' => 'Network BD',
            'location' => 'Dhaka',
            'category' => 'private',
            'experience_level' => 'mid',
            'short_description' => 'Network switches setup.',
            'source_name' => 'Bdjobs',
            'source_url' => 'https://example.com/net1',
            'job_hash' => ScrapedJob::generateHash('Network Admin', 'Network BD', now()->addDays(20)->format('Y-m-d')),
            'deadline' => now()->addDays(20)->format('Y-m-d'),
            'is_active' => true,
        ]);

        $response = $this->get('/jobs/'.$job->slug);
        $response->assertStatus(200);
        $response->assertSee('Cyber Security Analyst');
        $response->assertSee('Security BD');
        $response->assertSee('Apply on Original Site');
        $response->assertSee('Related Ongoing Jobs');
        $response->assertSee('Network Admin');
    }

    public function test_apply_redirect_records_applied_history_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $job = ScrapedJob::create([
            'title' => 'DevOps Engineer',
            'slug' => 'devops-engineer',
            'company_name' => 'Cloud BD',
            'location' => 'Dhaka',
            'category' => 'private',
            'experience_level' => 'mid',
            'short_description' => 'Kubernetes CI/CD',
            'source_name' => 'Skill.jobs',
            'source_url' => 'https://example.com/devops-apply',
            'job_hash' => ScrapedJob::generateHash('DevOps Engineer', 'Cloud BD', now()->addDays(10)->format('Y-m-d')),
            'deadline' => now()->addDays(10)->format('Y-m-d'),
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get('/jobs/'.$job->slug.'/apply');

        $response->assertRedirect('https://example.com/devops-apply');
        $this->assertDatabaseHas('applied_histories', [
            'user_id' => $user->id,
            'scraped_job_id' => $job->id,
        ]);
    }

    public function test_apply_redirect_deletes_expired_job_automatically(): void
    {
        $job = ScrapedJob::create([
            'title' => 'Expired Applied Job',
            'slug' => 'expired-applied-job',
            'company_name' => 'Old BD',
            'location' => 'Dhaka',
            'category' => 'private',
            'experience_level' => 'mid',
            'short_description' => 'Old job',
            'source_name' => 'Bdjobs',
            'source_url' => 'https://example.com/old',
            'job_hash' => ScrapedJob::generateHash('Expired Applied Job', 'Old BD', now()->subDays(3)->format('Y-m-d')),
            'deadline' => now()->subDays(3)->format('Y-m-d'),
            'is_active' => true,
        ]);

        $response = $this->get('/jobs/'.$job->slug.'/apply');
        $response->assertRedirect(route('jobs.index'));
        $this->assertDatabaseMissing('scraped_jobs', ['id' => $job->id]);
    }

    public function test_govt_hub_page_shows_only_govt_circulars(): void
    {
        ScrapedJob::create([
            'title' => 'Assistant Director',
            'slug' => 'assistant-director-bb',
            'company_name' => 'Bangladesh Bank',
            'location' => 'Dhaka',
            'category' => 'govt',
            'experience_level' => 'fresher',
            'vacancy' => 100,
            'short_description' => 'Bank recruitment',
            'source_name' => 'Teletalk',
            'source_url' => 'https://example.com/bb-ad',
            'job_hash' => ScrapedJob::generateHash('Assistant Director', 'Bangladesh Bank', now()->addDays(25)->format('Y-m-d')),
            'deadline' => now()->addDays(25)->format('Y-m-d'),
            'is_active' => true,
        ]);

        ScrapedJob::create([
            'title' => 'Private Sales Officer',
            'slug' => 'private-sales-officer',
            'company_name' => 'Private FMCG',
            'location' => 'Dhaka',
            'category' => 'private',
            'experience_level' => 'fresher',
            'vacancy' => 5,
            'short_description' => 'Sales recruitment',
            'source_name' => 'Bdjobs',
            'source_url' => 'https://example.com/sales',
            'job_hash' => ScrapedJob::generateHash('Private Sales Officer', 'Private FMCG', now()->addDays(25)->format('Y-m-d')),
            'deadline' => now()->addDays(25)->format('Y-m-d'),
            'is_active' => true,
        ]);

        $response = $this->get('/govt-jobs');
        $response->assertStatus(200);
        $response->assertSee('Assistant Director');
        $response->assertSee('Bangladesh Bank');
        $response->assertDontSee('Private Sales Officer');
    }

    public function test_guest_bookmarking_redirects_to_login_and_auto_saves_after_auth(): void
    {
        $job = ScrapedJob::create([
            'title' => 'Fullstack Laravel Developer',
            'slug' => 'fullstack-laravel-developer',
            'company_name' => 'Devs House',
            'location' => 'Dhaka',
            'category' => 'private',
            'experience_level' => 'mid',
            'short_description' => 'Laravel Vue',
            'source_name' => 'Bdjobs',
            'source_url' => 'https://example.com/laravel-dev',
            'job_hash' => ScrapedJob::generateHash('Fullstack Laravel Developer', 'Devs House', now()->addDays(14)->format('Y-m-d')),
            'deadline' => now()->addDays(14)->format('Y-m-d'),
            'is_active' => true,
        ]);

        // Guest attempts to save job
        $response = $this->post('/jobs/'.$job->id.'/save');
        $response->assertRedirect(route('login'));
        $this->assertEquals($job->id, session('redirect_save_job_id'));

        // Register new account with pending saved job in session
        $regResponse = $this->withSession(['redirect_save_job_id' => $job->id])
            ->post('/register', [
                'name' => 'New Seeker',
                'email' => 'newseeker@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $regResponse->assertRedirect(route('dashboard'));

        $user = User::where('email', 'newseeker@example.com')->first();
        $this->assertNotNull($user);
        $this->assertDatabaseHas('saved_jobs', [
            'user_id' => $user->id,
            'scraped_job_id' => $job->id,
        ]);
    }

    public function test_scraper_engine_validates_urls_and_skips_broken_links(): void
    {
        Http::fake([
            'https://working-site.com/*' => Http::response('OK', 200),
            'https://broken-site.com/*' => Http::response('Not Found', 404),
        ]);

        $scraper = app(JobScraperService::class);

        $mockBatch = [
            [
                'title' => 'Valid Link Job',
                'company_name' => 'Good Corp',
                'location' => 'Dhaka',
                'category' => 'private',
                'experience_level' => 'fresher',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://working-site.com/job1',
                'deadline' => now()->addDays(10)->format('Y-m-d'),
            ],
            [
                'title' => 'Broken Link Job',
                'company_name' => 'Bad Corp',
                'location' => 'Dhaka',
                'category' => 'private',
                'experience_level' => 'fresher',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://broken-site.com/job-404',
                'deadline' => now()->addDays(10)->format('Y-m-d'),
            ],
        ];

        $res = $scraper->processJobBatch('Bdjobs', $mockBatch);

        // 1 added (working link), 1 rejected (404 link)
        $this->assertEquals(1, $res['added']);
        $this->assertEquals(1, $res['invalid']);
        $this->assertDatabaseHas('scraped_jobs', ['source_url' => 'https://working-site.com/job1']);
        $this->assertDatabaseMissing('scraped_jobs', ['source_url' => 'https://broken-site.com/job-404']);
    }

    public function test_scraper_engine_rejects_expired_deadline_jobs(): void
    {
        Http::fake([
            '*' => Http::response('OK', 200),
        ]);

        $scraper = app(JobScraperService::class);

        $mockBatch = [
            [
                'title' => 'Already Expired Circular',
                'company_name' => 'Late Corp',
                'location' => 'Dhaka',
                'category' => 'private',
                'experience_level' => 'fresher',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://example.com/late-job',
                'deadline' => now()->subDay()->format('Y-m-d'), // Past deadline
            ],
        ];

        $res = $scraper->processJobBatch('Bdjobs', $mockBatch);

        $this->assertEquals(0, $res['added']);
        $this->assertEquals(1, $res['expired']);
        $this->assertDatabaseMissing('scraped_jobs', ['source_url' => 'https://example.com/late-job']);
    }

    public function test_scraper_engine_strict_duplicate_prevention(): void
    {
        Http::fake([
            '*' => Http::response('OK', 200),
        ]);

        $scraper = app(JobScraperService::class);

        $mockBatch = [
            [
                'title' => 'Duplicate Test Job',
                'company_name' => 'Duplicate Corp',
                'location' => 'Dhaka',
                'category' => 'private',
                'experience_level' => 'fresher',
                'salary_text' => 'Negotiable',
                'vacancy' => 1,
                'short_description' => 'First run will add, second will skip.',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://example.com/test-dup-1',
                'deadline' => now()->addDays(10)->format('Y-m-d'),
            ],
        ];

        // First ingestion run: 1 added, 0 skipped
        $res1 = $scraper->processJobBatch('Bdjobs', $mockBatch);
        $this->assertEquals(1, $res1['added']);
        $this->assertEquals(0, $res1['skipped']);

        // Second ingestion run with identical items: 0 added, 1 skipped!
        $res2 = $scraper->processJobBatch('Bdjobs', $mockBatch);
        $this->assertEquals(0, $res2['added']);
        $this->assertEquals(1, $res2['skipped']);

        $this->assertDatabaseHas('scraper_logs', [
            'source_name' => 'Bdjobs',
            'status' => 'success',
        ]);
    }

    public function test_expire_jobs_command_removes_outdated_jobs_from_database(): void
    {
        $expiredJob = ScrapedJob::create([
            'title' => 'Expired Vacancy',
            'slug' => 'expired-vacancy',
            'company_name' => 'Past Office',
            'location' => 'Sylhet',
            'category' => 'private',
            'experience_level' => 'fresher',
            'short_description' => 'Yesterday deadline',
            'source_name' => 'Bdjobs',
            'source_url' => 'https://example.com/expired-job',
            'job_hash' => ScrapedJob::generateHash('Expired Vacancy', 'Past Office', now()->subDay()->format('Y-m-d')),
            'deadline' => now()->subDay()->format('Y-m-d'),
            'is_active' => true,
        ]);

        Artisan::call('jobs:expire');

        // Automatically purged and removed permanently from database
        $this->assertDatabaseMissing('scraped_jobs', [
            'id' => $expiredJob->id,
        ]);
    }

    public function test_admin_control_panel_security_and_operations(): void
    {
        $regularUser = User::factory()->create(['role' => 'user']);
        $adminUser = User::factory()->create(['role' => 'admin']);

        // Regular user denied from admin dashboard
        $forbiddenResponse = $this->actingAs($regularUser)->get('/admin/dashboard');
        $forbiddenResponse->assertRedirect(route('login'));

        // Admin user allowed
        $adminResponse = $this->actingAs($adminUser)->get('/admin/dashboard');
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Scraper Engine');

        // Admin triggers purge of expired jobs
        $purgeResponse = $this->actingAs($adminUser)->post('/admin/purge-expired');
        $purgeResponse->assertRedirect(route('admin.dashboard'));
        $purgeResponse->assertSessionHas('success');

        // Admin creates job and toggles status
        $job = ScrapedJob::create([
            'title' => 'Toggle Test Job',
            'slug' => 'toggle-test-job',
            'company_name' => 'Toggle Corp',
            'location' => 'Dhaka',
            'category' => 'private',
            'experience_level' => 'fresher',
            'short_description' => 'Toggle me',
            'source_name' => 'Bdjobs',
            'source_url' => 'https://example.com/toggle-job',
            'job_hash' => ScrapedJob::generateHash('Toggle Test Job', 'Toggle Corp', now()->addDays(5)->format('Y-m-d')),
            'deadline' => now()->addDays(5)->format('Y-m-d'),
            'is_active' => true,
        ]);

        $toggleResponse = $this->actingAs($adminUser)->patch('/admin/jobs/'.$job->id.'/toggle-status');
        $toggleResponse->assertRedirect();
        $this->assertFalse($job->fresh()->is_active);

        // Admin deletes job
        $deleteResponse = $this->actingAs($adminUser)->delete('/admin/jobs/'.$job->id);
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('scraped_jobs', ['id' => $job->id]);
    }
}
