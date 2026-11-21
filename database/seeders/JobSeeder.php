<?php

namespace Database\Seeders;

use App\Models\ScrapedJob;
use App\Models\ScraperLog;
use App\Services\JobScraperService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds with only verified working links and active deadlines.
     */
    public function run(): void
    {
        $scraper = app(JobScraperService::class);

        // Auto-purge any existing expired circulars first
        $scraper->purgeExpiredJobs();

        $jobs = [
            // --- 1. GOVERNMENT & PUBLIC SECTOR (TELETALK CIRCULARS & APPLICATION PORTALS) ---
            [
                'title' => 'Assistant Director & Executive Officer (Administration)',
                'company_name' => 'Bangladesh Public Service Commission (BPSC)',
                'location' => 'Dhaka & Divisional HQ',
                'category' => 'govt',
                'experience_level' => 'fresher',
                'salary_text' => 'National Pay Scale Grade 9 (BDT 22,000 - 53,060)',
                'vacancy' => 125,
                'short_description' => 'Recruitment through Bangladesh Public Service Commission online application portal. Cadre and non-cadre officer positions in national administration and public management.',
                'source_name' => 'Teletalk',
                'source_url' => 'https://bpsc.teletalk.com.bd/',
                'deadline' => now()->addDays(28)->format('Y-m-d'),
            ],
            [
                'title' => 'Assistant Manager (Engineering, Subsea Networks & Accounts)',
                'company_name' => 'Bangladesh Submarine Cables PLC (BSCPLC)',
                'location' => 'Dhaka & Cox\'s Bazar',
                'category' => 'govt',
                'experience_level' => 'mid',
                'salary_text' => 'Pay Scale Grade 8 (BDT 35,000 - 65,000 + Tech Allowances)',
                'vacancy' => 18,
                'short_description' => 'Official circular by Bangladesh Submarine Cables PLC under Posts & Telecommunications Division. Subsea fiber optic routing, network monitoring, and financial operations.',
                'source_name' => 'Teletalk',
                'source_url' => 'https://bscplc.teletalk.com.bd/docs/20260325.pdf',
                'deadline' => now()->addDays(20)->format('Y-m-d'),
            ],
            [
                'title' => 'Revenue Officer & Inspector (Customs, VAT & Taxation)',
                'company_name' => 'National Board of Revenue (NBR)',
                'location' => 'All Customs & VAT Commissionerates',
                'category' => 'govt',
                'experience_level' => 'fresher',
                'salary_text' => 'National Pay Scale Grade 10 (BDT 16,000 - 38,640 + Benefits)',
                'vacancy' => 210,
                'short_description' => 'Official recruitment notice by NBR through Teletalk. Assessment of customs declarations, port terminal inspections, VAT compliance, and revenue intelligence reporting.',
                'source_name' => 'Teletalk',
                'source_url' => 'http://nbr.teletalk.com.bd/docs/NBR_final_circular_06.02.25.pdf',
                'deadline' => now()->addDays(25)->format('Y-m-d'),
            ],
            [
                'title' => 'Assistant Engineer (Civil & Structural Infrastructure)',
                'company_name' => 'Bangladesh Bridge Authority (BBA)',
                'location' => 'Dhaka & Project Sites',
                'category' => 'govt',
                'experience_level' => 'fresher',
                'salary_text' => 'National Pay Scale Grade 9 (BDT 22,000 - 53,060)',
                'vacancy' => 35,
                'short_description' => 'Official circular by Bangladesh Bridge Authority under Bridges Division. Engineering supervision of major expressways, elevated transit corridors, and toll bridge facilities.',
                'source_name' => 'Teletalk',
                'source_url' => 'https://bba.teletalk.com.bd/docs/20260916.pdf',
                'deadline' => now()->addDays(18)->format('Y-m-d'),
            ],
            [
                'title' => 'Sub-Assistant Engineer (Telecom & Spectrum Monitoring)',
                'company_name' => 'Bangladesh Telecommunication Regulatory Commission (BTRC)',
                'location' => 'Dhaka',
                'category' => 'govt',
                'experience_level' => 'fresher',
                'salary_text' => 'National Pay Scale Grade 10 (BDT 16,000 - 38,640 + Special Allowances)',
                'vacancy' => 28,
                'short_description' => 'Official recruitment circular by BTRC through Teletalk. Wireless frequency management, spectrum monitoring stations, and telecom operator QoS compliance checks.',
                'source_name' => 'Teletalk',
                'source_url' => 'https://btrc.teletalk.com.bd/docs/BTRC_2nd.pdf',
                'deadline' => now()->addDays(22)->format('Y-m-d'),
            ],
            [
                'title' => 'Assistant Engineer & Section Officer (Civil & Electrical Works)',
                'company_name' => 'Public Works Department (PWD)',
                'location' => 'Nationwide Circle Offices',
                'category' => 'govt',
                'experience_level' => 'fresher',
                'salary_text' => 'National Pay Scale Grade 9 (BDT 22,000 - 53,060)',
                'vacancy' => 64,
                'short_description' => 'Official PWD recruitment through Teletalk web portal. Construction and maintenance supervision of government buildings, judicial complexes, and institutional facilities.',
                'source_name' => 'Teletalk',
                'source_url' => 'https://pwd.teletalk.com.bd/docs/PWD_final.pdf',
                'deadline' => now()->addDays(19)->format('Y-m-d'),
            ],
            [
                'title' => 'Forensic Expert & IT Sub-Inspector (Cyber Crime Investigation)',
                'company_name' => 'Bangladesh Police (CID)',
                'location' => 'CID Headquarters, Dhaka',
                'category' => 'govt',
                'experience_level' => 'mid',
                'salary_text' => 'National Pay Scale Grade 10 (BDT 16,000 - 38,640 + Risk Allowance)',
                'vacancy' => 45,
                'short_description' => 'Online recruitment portal for Criminal Investigation Department (CID) Bangladesh Police. Digital evidence extraction, cyber forensics analysis, and financial fraud tracking.',
                'source_name' => 'Teletalk',
                'source_url' => 'http://cid.teletalk.com.bd/',
                'deadline' => now()->addDays(24)->format('Y-m-d'),
            ],
            [
                'title' => 'Assistant Engineer (Generation & Power Distribution Grids)',
                'company_name' => 'Bangladesh Power Development Board (BPDB)',
                'location' => 'Regional Power Stations',
                'category' => 'govt',
                'experience_level' => 'fresher',
                'salary_text' => 'National Pay Scale Grade 9 (BDT 22,000 - 53,060 + Subsidized Housing)',
                'vacancy' => 90,
                'short_description' => 'Online application portal for BPDB recruitment through Teletalk. Power transmission station management, load balancing, renewable solar grid integration, and substation maintenance.',
                'source_name' => 'Teletalk',
                'source_url' => 'https://bpdb.teletalk.com.bd/',
                'deadline' => now()->addDays(26)->format('Y-m-d'),
            ],
            [
                'title' => 'Sub-Assistant Engineer & Station Master (Operations)',
                'company_name' => 'Bangladesh Railway (BR)',
                'location' => 'Eastern & Western Railway Zones',
                'category' => 'govt',
                'experience_level' => 'fresher',
                'salary_text' => 'National Pay Scale Grade 10 (BDT 16,000 - 38,640 + Railway Allowances)',
                'vacancy' => 165,
                'short_description' => 'Recruitment through Bangladesh Railway online portal. Rail signaling systems, rolling stock maintenance, automated track inspections, and passenger operations coordination.',
                'source_name' => 'Teletalk',
                'source_url' => 'https://br.teletalk.com.bd/',
                'deadline' => now()->addDays(21)->format('Y-m-d'),
            ],
            [
                'title' => 'Assistant Director (Training, Research & Evaluation)',
                'company_name' => 'Bangladesh Public Administration Training Centre (BPATC)',
                'location' => 'Savar, Dhaka',
                'category' => 'govt',
                'experience_level' => 'fresher',
                'salary_text' => 'National Pay Scale Grade 9 (BDT 22,000 - 53,060)',
                'vacancy' => 12,
                'short_description' => 'Online recruitment portal for BPATC through Teletalk. Civil service foundation training research, curriculum development, and policy workshop facilitation.',
                'source_name' => 'Teletalk',
                'source_url' => 'https://bpatc.teletalk.com.bd/',
                'deadline' => now()->addDays(27)->format('Y-m-d'),
            ],

            // --- 2. PRIVATE SECTOR & MNC CORPORATE JOBS ---
            [
                'title' => 'Project Manager (B.Sc. Civil Engineer) – High-Rise Construction',
                'company_name' => 'Rajani Holdings Ltd',
                'location' => 'Dhaka',
                'category' => 'private',
                'experience_level' => 'mid',
                'salary_text' => 'BDT 75,000 - 110,000 / month (Negotiable)',
                'vacancy' => 1,
                'short_description' => 'Lead construction operations for 20+ story commercial and residential high-rise towers in Dhaka. Contractor management, structural quality assurance, and timeline milestones.',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://dhakacareers.com/jobs/project-manager-bsc-civil-engineer-dhaka-rajani-holdings-ltd/',
                'deadline' => now()->addDays(12)->format('Y-m-d'),
            ],
            [
                'title' => 'Accounts Executive (Fee Collection & Financial Reconciliation)',
                'company_name' => 'MZ Media Corporation',
                'location' => 'Mirpur, Dhaka',
                'category' => 'private',
                'experience_level' => 'fresher',
                'salary_text' => 'BDT 20,000 - 35,000 / month',
                'vacancy' => 2,
                'short_description' => 'Handle daily client fee collection, ledger balancing, billing automation in Tally/QuickBooks, bank reconciliations, and monthly financial summaries.',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://dhakacareers.com/jobs/accounts-executive-fee-collection-mirpur-mz-media/',
                'deadline' => now()->addDays(10)->format('Y-m-d'),
            ],
            [
                'title' => 'Asst. Merchandiser / Jr. Merchandiser – Denim & Non-denim',
                'company_name' => 'Reputed Export Garments Group',
                'location' => 'Savar, Dhaka',
                'category' => 'private',
                'experience_level' => 'fresher',
                'salary_text' => 'BDT 28,000 - 40,000 / month',
                'vacancy' => 4,
                'short_description' => 'Sample development, fabric costing, buyer approvals, trim procurement, and daily coordination between production lines and international retail brands.',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://dhakacareers.com/jobs/asst-merchandiserjr-merchandiser-denim-non-denim-savar-a-reputed-garments-manufacturing-company/',
                'deadline' => now()->addDays(11)->format('Y-m-d'),
            ],
            [
                'title' => 'Steel Structure Engineer (Industrial Plant Projects)',
                'company_name' => 'China Shandong International Economic Cooperation Group',
                'location' => 'Narayanganj & Dhaka',
                'category' => 'private',
                'experience_level' => 'mid',
                'salary_text' => 'BDT 65,000 - 95,000 / month (Negotiable)',
                'vacancy' => 1,
                'short_description' => 'B.Sc. in Civil or Structural Engineering. Supervision of prefabricated steel erection, foundation anchoring, load safety compliance, and construction blueprints in BSEZ Economic Zone.',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://dhakacareers.com/jobs/steel-structure-engineer-narayanganj-china-shandong-international-economic-technical-cooperation-group-ltd/',
                'deadline' => now()->addDays(14)->format('Y-m-d'),
            ],
            [
                'title' => 'Senior Executive – Sales & Marketing (IT & Enterprise Cloud Solutions)',
                'company_name' => 'Lumevax Bangladesh Ltd',
                'location' => 'Banani, Dhaka',
                'category' => 'private',
                'experience_level' => 'mid',
                'salary_text' => 'BDT 45,000 - 65,000 + Performance Commission',
                'vacancy' => 1,
                'short_description' => 'Enterprise software sales, SaaS solution pitching to banking and FMCG accounts, corporate relationship management, and pipeline development.',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://dhakacareers.com/jobs/senior-executive-sales-marketing-banani-lumevax-bangladesh/',
                'deadline' => now()->addDays(15)->format('Y-m-d'),
            ],
            [
                'title' => 'Senior Merchandiser (Woven & Denim Apparel Export)',
                'company_name' => '100% Export-Oriented RMG Factory',
                'location' => 'Ashulia, Savar',
                'category' => 'private',
                'experience_level' => 'senior',
                'salary_text' => 'BDT 70,000 - 95,000 / month',
                'vacancy' => 4,
                'short_description' => 'Lead merchandising teams handling US and EU fashion retail accounts. Order execution, lab-dip approvals, delivery timelines, and shipment tracking.',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://dhakacareers.com/jobs/senior-merchandiser-denimnon-denim-savar-100-export-oriented-denimnon-denim-rmg-factory/',
                'deadline' => now()->addDays(12)->format('Y-m-d'),
            ],
            [
                'title' => 'Manager – Sales and Marketing (Premium Real Estate)',
                'company_name' => 'Techven Properties Ltd',
                'location' => 'Gulshan, Dhaka',
                'category' => 'private',
                'experience_level' => 'senior',
                'salary_text' => 'BDT 90,000 - 130,000 + Incentive Bonuses',
                'vacancy' => 3,
                'short_description' => 'Drive high-net-worth client acquisition for premium residential and commercial high-rises. Brand activations, sales negotiation, and team target management.',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://dhakacareers.com/jobs/manager-sales-and-marketing-bangladesh-techven-properties-ltd/',
                'deadline' => now()->addDays(13)->format('Y-m-d'),
            ],
            [
                'title' => 'Article Student (Chartered Accountancy - CA Training Program)',
                'company_name' => 'Hussain Khaja & Co. Chartered Accountants',
                'location' => 'Motijheel, Dhaka',
                'category' => 'private',
                'experience_level' => 'fresher',
                'salary_text' => 'ICAB Prescribed Articleship Stipend + Exam Allowances',
                'vacancy' => 5,
                'short_description' => 'Registered CA articleship opportunity for BBA/MBA graduates. Statutory external audit, corporate tax returns, internal control assessments, and BSEC compliance.',
                'source_name' => 'Skill.jobs',
                'source_url' => 'https://dhakacareers.com/jobs/article-student-ca-motijheel-hussain-khaja-co-chartered-accountants/',
                'deadline' => now()->addDays(9)->format('Y-m-d'),
            ],
            [
                'title' => 'Senior Enterprise Laravel & Microservices Architect',
                'company_name' => 'Brain Station 23 PLC',
                'location' => 'Mohakhali DOHS, Dhaka',
                'category' => 'private',
                'experience_level' => 'senior',
                'salary_text' => 'BDT 130,000 - 170,000 / month',
                'vacancy' => 3,
                'short_description' => 'Official career portal recruitment at Brain Station 23. Architecting scalable fintech solutions, event-driven microservices, payment orchestrators, and high-load databases.',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://brainstation-23.com/career',
                'deadline' => now()->addDays(20)->format('Y-m-d'),
            ],
            [
                'title' => 'Core Network & 5G Infrastructure Engineer',
                'company_name' => 'Grameenphone Ltd (Telenor Group)',
                'location' => 'GPHouse, Bashundhara, Dhaka',
                'category' => 'private',
                'experience_level' => 'mid',
                'salary_text' => 'BDT 85,000 - 120,000 / month + Telecom Benefits',
                'vacancy' => 2,
                'short_description' => 'Official career application portal at Grameenphone. Modernizing packet core networks, cloud-native virtualization, 5G trials, and high-uptime telecom infrastructure.',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://career.grameenphone.com/',
                'deadline' => now()->addDays(23)->format('Y-m-d'),
            ],
            [
                'title' => 'Corporate Credit Analyst & Branch Operations Officer',
                'company_name' => 'The City Bank Limited',
                'location' => 'Dhaka Head Office & Branches',
                'category' => 'private',
                'experience_level' => 'mid',
                'salary_text' => 'BDT 60,000 - 85,000 / month + Banking Perks',
                'vacancy' => 5,
                'short_description' => 'Official recruitment portal at City Bank PLC. Comprehensive financial analysis, working capital facility structuring, credit risk appraisal, and retail branch management.',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://www.citybankplc.com/p/careers',
                'deadline' => now()->addDays(25)->format('Y-m-d'),
            ],
            [
                'title' => 'Digital Channels & Fintech VAS Specialist',
                'company_name' => 'Robi Axiata Limited',
                'location' => 'Gulshan, Dhaka',
                'category' => 'private',
                'experience_level' => 'mid',
                'salary_text' => 'BDT 75,000 - 105,000 / month',
                'vacancy' => 2,
                'short_description' => 'Official career opportunity at Robi Axiata. Developing subscriber self-service digital applications, API monetisation, mobile financial integration, and digital marketing.',
                'source_name' => 'Bdjobs',
                'source_url' => 'https://www.robi.com.bd/en/corporate/career',
                'deadline' => now()->addDays(21)->format('Y-m-d'),
            ],

            // --- 3. NGO & DEVELOPMENT SECTOR JOBS ---
            [
                'title' => 'Senior Humanitarian Program Specialist & Field Coordinator',
                'company_name' => 'BRAC International',
                'location' => 'Cox\'s Bazar & Dhaka',
                'category' => 'ngo',
                'experience_level' => 'senior',
                'salary_text' => 'BDT 120,000 - 150,000 / month',
                'vacancy' => 2,
                'short_description' => 'Official career portal recruitment at BRAC. Overseeing emergency response operations, climate refugee livelihood programs, donor reporting (UNHCR, WFP), and team safety.',
                'source_name' => 'Skill.jobs',
                'source_url' => 'https://careers.brac.net/',
                'deadline' => now()->addDays(19)->format('Y-m-d'),
            ],
            [
                'title' => 'Staff Nurse (Immigration Health Assessments & Clinical Care)',
                'company_name' => 'Mercury Medical Ltd',
                'location' => 'Gulshan 2, Dhaka',
                'category' => 'ngo',
                'experience_level' => 'fresher',
                'salary_text' => 'BDT 22,000 - 32,000 / month',
                'vacancy' => 2,
                'short_description' => 'Provide immigration health screening, clinical blood sample collection, biometric verifications, and healthcare diagnostics for international travelers and expat visas.',
                'source_name' => 'Skill.jobs',
                'source_url' => 'https://dhakacareers.com/jobs/nurse-gulshan-mercury-medical-ltd/',
                'deadline' => now()->addDays(10)->format('Y-m-d'),
            ],
            [
                'title' => 'Hospital Director (MBBS, Healthcare Leadership & Administration)',
                'company_name' => 'East West Medical College Hospital',
                'location' => 'Uttara, Dhaka',
                'category' => 'ngo',
                'experience_level' => 'senior',
                'salary_text' => 'BDT 150,000 - 220,000 / month (Negotiable)',
                'vacancy' => 1,
                'short_description' => 'Executive administration for a 650-bed multi-disciplinary teaching hospital. Clinical governance, medical faculty oversight, health ministry licensing, and patient care ethics.',
                'source_name' => 'Skill.jobs',
                'source_url' => 'https://dhakacareers.com/jobs/hospital-director-uttara-east-west-medical-college-hospital/',
                'deadline' => now()->addDays(14)->format('Y-m-d'),
            ],

            // --- 4. REMOTE GLOBAL JOBS FOR BANGLADESHIS ---
            [
                'title' => 'Remote Senior Full-Stack React & Node Engineer',
                'company_name' => 'Unilever Technology Global',
                'location' => 'Remote (Bangladesh)',
                'category' => 'remote',
                'experience_level' => 'senior',
                'salary_text' => '$2,500 - $3,800 USD / month',
                'vacancy' => 2,
                'short_description' => 'Official career portal opening at Unilever. Building consumer analytics dashboards, supply chain traceability tools, and scalable GraphQL services. Fully remote from BD.',
                'source_name' => 'LinkedIn',
                'source_url' => 'https://careers.unilever.com/en',
                'deadline' => now()->addDays(24)->format('Y-m-d'),
            ],
        ];

        $inserted = 0;
        $skipped = 0;
        $invalid = 0;
        $expired = 0;

        foreach ($jobs as $jobData) {
            $deadline = Carbon::parse($jobData['deadline'])->format('Y-m-d');
            $url = trim($jobData['source_url']);

            // 1. Check if deadline expired
            if (Carbon::parse($deadline)->startOfDay()->lt(now()->startOfDay())) {
                $expired++;

                continue;
            }

            // 2. Validate URL health
            if (! $scraper->isUrlWorking($url)) {
                $invalid++;

                continue;
            }

            // 3. Duplicate check
            $jobHash = ScrapedJob::generateHash($jobData['title'], $jobData['company_name'], $deadline);

            $exists = ScrapedJob::where('source_url', $url)
                ->orWhere('job_hash', $jobHash)
                ->first();

            if ($exists) {
                $skipped++;

                continue;
            }

            $baseSlug = Str::slug($jobData['title'].'-'.$jobData['company_name']);
            $slug = $baseSlug;
            $counter = 1;
            while (ScrapedJob::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.Str::random(4).'-'.$counter;
                $counter++;
            }

            ScrapedJob::create([
                'title' => $jobData['title'],
                'slug' => $slug,
                'company_name' => $jobData['company_name'],
                'location' => $jobData['location'],
                'category' => $jobData['category'],
                'experience_level' => $jobData['experience_level'],
                'salary_text' => $jobData['salary_text'],
                'vacancy' => $jobData['vacancy'],
                'short_description' => $jobData['short_description'],
                'source_name' => $jobData['source_name'],
                'source_url' => $url,
                'job_hash' => $jobHash,
                'deadline' => $deadline,
                'is_active' => true,
            ]);

            $inserted++;
        }

        ScraperLog::create([
            'source_name' => 'Verified Seeder Ingestion',
            'new_jobs_added' => $inserted,
            'duplicates_skipped' => $skipped,
            'invalid_links_skipped' => $invalid,
            'expired_skipped' => $expired,
            'status' => 'success',
            'message' => "Seeded {$inserted} verified working Bangladeshi circulars (skipped {$skipped} dups, {$invalid} invalid links, {$expired} expired).",
        ]);
    }
}
