<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wedding_albums', function (Blueprint $table) {
            $table->boolean('enable_face_ai')->default(true)->after('allow_downloads');
        });
    }

    public function down(): void
    {
        Schema::table('wedding_albums', function (Blueprint $table) {
            $table->dropColumn(['enable_face_ai']);
        });
    }
};