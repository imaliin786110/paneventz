<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            if (!Schema::hasColumn('stories', 'gallery')) {
                $table->json('gallery')->nullable();
            }
        });

        Schema::table('website_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('website_settings', 'logo')) {
                $table->string('logo')->nullable();
            }
            if (!Schema::hasColumn('website_settings', 'favicon')) {
                $table->string('favicon')->nullable();
            }
            if (!Schema::hasColumn('website_settings', 'hero_background_image')) {
                $table->string('hero_background_image')->nullable();
            }
            if (!Schema::hasColumn('website_settings', 'hero_background_video')) {
                $table->string('hero_background_video')->nullable();
            }
            if (!Schema::hasColumn('website_settings', 'analytics_code')) {
                $table->text('analytics_code')->nullable();
            }
        });
    }

    public function down(): void
    {
    }
};