<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('scraper_logs', function (Blueprint $table) {
            $table->unsignedInteger('invalid_links_skipped')->default(0)->after('duplicates_skipped');
            $table->unsignedInteger('expired_skipped')->default(0)->after('invalid_links_skipped');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scraper_logs', function (Blueprint $table) {
            $table->dropColumn(['invalid_links_skipped', 'expired_skipped']);
        });
    }
};
