<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScraperLog extends Model
{
    use HasFactory;

    protected $table = 'scraper_logs';

    protected $fillable = [
        'source_name',
        'new_jobs_added',
        'duplicates_skipped',
        'invalid_links_skipped',
        'expired_skipped',
        'status',
        'message',
    ];

    protected function casts(): array
    {
        return [
            'new_jobs_added' => 'integer',
            'duplicates_skipped' => 'integer',
            'invalid_links_skipped' => 'integer',
            'expired_skipped' => 'integer',
        ];
    }
}
