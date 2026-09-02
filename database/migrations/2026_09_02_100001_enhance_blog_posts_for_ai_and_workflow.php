<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('status', 30)->default('draft')->after('is_published');
            $table->string('focus_keyword')->nullable()->after('status');
            $table->json('secondary_keywords')->nullable()->after('focus_keyword');
            $table->json('ai_generation_meta')->nullable()->after('secondary_keywords');
            $table->unsignedTinyInteger('quality_score')->nullable()->after('ai_generation_meta');
            $table->json('quality_warnings')->nullable()->after('quality_score');
            $table->foreignId('source_story_id')->nullable()->constrained('stories')->nullOnDelete()->after('quality_warnings');
            $table->foreignId('source_wedding_album_id')->nullable()->constrained('wedding_albums')->nullOnDelete()->after('source_story_id');
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['source_story_id']);
            $table->dropForeign(['source_wedding_album_id']);
            $table->dropColumn([
                'status',
                'focus_keyword',
                'secondary_keywords',
                'ai_generation_meta',
                'quality_score',
                'quality_warnings',
                'source_story_id',
                'source_wedding_album_id',
            ]);
        });
    }
};
