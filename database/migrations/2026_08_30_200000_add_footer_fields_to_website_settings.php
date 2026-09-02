<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('footer_heading')->nullable()->after('about_description');
            $table->text('footer_description')->nullable()->after('footer_heading');
            $table->string('footer_address')->nullable()->after('footer_description');
            $table->string('footer_copyright')->nullable()->after('footer_address');
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['footer_heading', 'footer_description', 'footer_address', 'footer_copyright']);
        });
    }
};