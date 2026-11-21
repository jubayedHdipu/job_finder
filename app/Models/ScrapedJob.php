<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ScrapedJob extends Model
{
    use HasFactory;

    protected $table = 'scraped_jobs';

    protected $fillable = [
        'title',
        'slug',
        'company_name',
        'location',
        'category',
        'experience_level',
        'salary_text',
        'vacancy',
        'short_description',
        'source_name',
        'source_url',
        'job_hash',
        'deadline',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'is_active' => 'boolean',
            'vacancy' => 'integer',
        ];
    }

    /**
     * Scope query to only ongoing jobs (active and deadline >= today).
     */
    public function scopeOngoing(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->whereDate('deadline', '>=', now()->toDateString());
    }

    /**
     * Scope query to jobs ending within $days days.
     */
    public function scopeEndingSoon(Builder $query, int $days = 3): Builder
    {
        return $query->where('is_active', true)
            ->whereBetween('deadline', [
                now()->toDateString(),
                now()->addDays($days)->toDateString(),
            ]);
    }

    /**
     * Scope query to government/public sector jobs.
     */
    public function scopeGovt(Builder $query): Builder
    {
        return $query->where('category', 'govt');
    }

    public function savedByUsers(): HasMany
    {
        return $this->hasMany(SavedJob::class, 'scraped_job_id');
    }

    public function appliedHistories(): HasMany
    {
        return $this->hasMany(AppliedHistory::class, 'scraped_job_id');
    }

    public function getDaysRemainingAttribute(): int
    {
        if (! $this->deadline) {
            return 0;
        }

        $now = now()->startOfDay();
        $deadline = Carbon::parse($this->deadline)->startOfDay();

        return (int) $now->diffInDays($deadline, false);
    }

    public function getIsExpiredAttribute(): bool
    {
        if (! $this->deadline) {
            return true;
        }

        return Carbon::parse($this->deadline)->startOfDay()->lt(now()->startOfDay());
    }

    public function getDeadlineHumanAttribute(): string
    {
        $remaining = $this->days_remaining;

        if ($remaining < 0) {
            return 'Expired';
        }

        if ($remaining === 0) {
            return 'Ends Today!';
        }

        if ($remaining === 1) {
            return '1 day left';
        }

        return "{$remaining} days left";
    }

    /**
     * Generate composite hash to prevent duplicates.
     */
    public static function generateHash(string $title, string $company, string $deadline): string
    {
        $normalizedTitle = Str::lower(trim(preg_replace('/\s+/', ' ', $title)));
        $normalizedCompany = Str::lower(trim(preg_replace('/\s+/', ' ', $company)));
        $normalizedDeadline = Carbon::parse($deadline)->format('Y-m-d');

        return hash('sha256', "{$normalizedTitle}|{$normalizedCompany}|{$normalizedDeadline}");
    }
}
