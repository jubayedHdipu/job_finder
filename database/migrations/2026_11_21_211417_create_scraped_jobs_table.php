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
        Schema::create('scraped_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('company_name');
            $table->string('location');
            $table->string('category', 50)->default('private');
            $table->string('experience_level', 50)->default('fresher');
            $table->string('salary_text')->nullable();
            $table->unsignedInteger('vacancy')->nullable();
            $table->text('short_description');
            $table->string('source_name', 100);
            $table->string('source_url', 500)->unique();
            $table->string('job_hash', 64)->unique();
            $table->date('deadline');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'deadline']);
            $table->index('category');
            $table->index('experience_level');
            $table->index('source_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scraped_jobs');
    }
};
