<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_metadatas', function (Blueprint $table): void {
            $table->id();
            $table->nullableMorphs('seoable'); // seoable_type, seoable_id
            $table->string('route_name')->nullable()->index(); // for static named routes (e.g. home, services, terms)
            
            // Core Meta
            $table->string('title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots')->default('index, follow');
            
            // Open Graph (Facebook / WhatsApp / LinkedIn)
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website');
            
            // Twitter / X Cards
            $table->string('twitter_card')->default('summary_large_image');
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            
            // Structured Data / Schema
            $table->string('schema_type')->nullable(); // e.g. PhotographyBusiness, Article, Service, CollectionPage
            $table->json('custom_json_ld')->nullable();
            
            // Sitemap Directives
            $table->string('change_frequency')->default('weekly'); // always, hourly, daily, weekly, monthly, yearly, never
            $table->decimal('priority', 2, 1)->default(0.8);
            $table->boolean('is_indexed')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_metadatas');
    }
};
