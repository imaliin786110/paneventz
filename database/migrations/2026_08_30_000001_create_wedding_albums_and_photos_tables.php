<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('couple_names')->nullable();
            $table->string('cover_image')->nullable();
            $table->date('event_date')->nullable();
            $table->string('location')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('google_drive_folder_id')->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('allow_downloads')->default(true);
            $table->timestamps();
        });

        Schema::create('album_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_album_id')->constrained('wedding_albums')->onDelete('cascade');
            $table->string('photo_url');
            $table->string('thumbnail_url')->nullable();
            $table->string('file_name')->nullable();
            $table->json('face_descriptors')->nullable();
            $table->integer('faces_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('album_photos');
        Schema::dropIfExists('wedding_albums');
    }
};