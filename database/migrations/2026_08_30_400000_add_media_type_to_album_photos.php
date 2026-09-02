<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('album_photos', function (Blueprint $table) {
            $table->boolean('is_video')->default(false)->after('file_name');
            $table->string('file_size')->nullable()->after('is_video');
        });
    }

    public function down(): void
    {
        Schema::table('album_photos', function (Blueprint $table) {
            $table->dropColumn(['is_video', 'file_size']);
        });
    }
};