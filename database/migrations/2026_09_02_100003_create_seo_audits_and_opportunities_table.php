<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_health_issues', function (Blueprint $table) {
            $table->id();
            $table->string('item_type', 60); // Page, Blog, Gallery, Service, Location, Technical
            $table->string('item_title');
            $table->string('url')->nullable();
            $table->string('edit_url')->nullable();
            $table->string('issue_code', 60); // missing_title, missing_description, broken_link, orphan, thin_content, duplicate_title, etc.
            $table->string('message');
            $table->string('severity', 20)->default('warning'); // critical, warning, info
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('last_detected_at')->nullable();
            $table->timestamps();
        });

        Schema::create('search_console_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('query')->nullable();
            $table->string('page_url')->nullable();
            $table->unsignedInteger('clicks')->default(0);
            $table->unsignedInteger('impressions')->default(0);
            $table->decimal('ctr', 5, 2)->default(0.00);
            $table->decimal('position', 5, 2)->default(0.00);
            $table->string('opportunity_type', 40)->nullable(); // high_ctr_opportunity, striking_distance, content_gap
            $table->text('ai_recommendation')->nullable();
            $table->date('metric_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_console_metrics');
        Schema::dropIfExists('seo_health_issues');
    }
};
