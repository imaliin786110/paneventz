<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('website_settings', 'brochure_pdf')) {
                $table->string('brochure_pdf')->nullable();
            }
        });

        if (!Schema::hasTable('blocked_dates')) {
            Schema::create('blocked_dates', function (Blueprint $table) {
                $table->id();
                $table->date('date');
                $table->string('title');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
    }
};