<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function savedJobs(): HasMany
    {
        return $this->hasMany(SavedJob::class);
    }

    public function savedJobPosts(): BelongsToMany
    {
        return $this->belongsToMany(ScrapedJob::class, 'saved_jobs', 'user_id', 'scraped_job_id')
            ->withTimestamps();
    }

    public function appliedHistories(): HasMany
    {
        return $this->hasMany(AppliedHistory::class)->latest('clicked_at');
    }

    public function hasSavedJob(int $jobId): bool
    {
        return $this->savedJobs()->where('scraped_job_id', $jobId)->exists();
    }

    public function hasApplied(int $jobId): bool
    {
        return $this->appliedHistories()->where('scraped_job_id', $jobId)->exists();
    }
}
