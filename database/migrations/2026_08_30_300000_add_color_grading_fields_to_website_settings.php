<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('color_grade_heading')->nullable()->after('about_description');
            $table->text('color_grade_description')->nullable()->after('color_grade_heading');
            $table->string('color_grade_before_image')->nullable()->after('color_grade_description');
            $table->string('color_grade_after_image')->nullable()->after('color_grade_before_image');
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['color_grade_heading', 'color_grade_description', 'color_grade_before_image', 'color_grade_after_image']);
        });
    }
};