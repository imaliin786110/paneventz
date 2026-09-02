<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->integer('stat_1_number')->default(250)->after('color_grade_after_image');
            $table->string('stat_1_suffix')->default('+')->after('stat_1_number');
            $table->string('stat_1_label')->default('Weddings & Marriages Documented')->after('stat_1_suffix');

            $table->integer('stat_2_number')->default(10)->after('stat_1_label');
            $table->string('stat_2_suffix')->default('+')->after('stat_2_number');
            $table->string('stat_2_label')->default('Years of Artistic Experience')->after('stat_2_suffix');

            $table->integer('stat_3_number')->default(35)->after('stat_2_label');
            $table->string('stat_3_suffix')->default('+')->after('stat_3_number');
            $table->string('stat_3_label')->default('Royal Palaces & Destinations')->after('stat_3_suffix');

            $table->integer('stat_4_number')->default(100)->after('stat_3_label');
            $table->string('stat_4_suffix')->default('%')->after('stat_4_number');
            $table->string('stat_4_label')->default('Client Trust & Handcrafted Heirlooms')->after('stat_4_suffix');
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'stat_1_number', 'stat_1_suffix', 'stat_1_label',
                'stat_2_number', 'stat_2_suffix', 'stat_2_label',
                'stat_3_number', 'stat_3_suffix', 'stat_3_label',
                'stat_4_number', 'stat_4_suffix', 'stat_4_label',
            ]);
        });
    }
};