<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wedding_albums', function (Blueprint $table) {
            $table->string('guest_google_drive_folder_id')->nullable()->after('google_drive_folder_id');
        });
    }

    public function down(): void
    {
        Schema::table('wedding_albums', function (Blueprint $table) {
            $table->dropColumn(['guest_google_drive_folder_id']);
        });
    }
};