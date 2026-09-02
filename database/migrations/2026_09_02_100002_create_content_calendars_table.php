<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('topic');
            $table->string('target_keyword')->nullable();
            $table->string('category', 60)->default('Wedding Photography');
            $table->string('author_name', 100)->default('Paneventz Editorial');
            $table->date('scheduled_for');
            $table->string('status', 30)->default('planned'); // planned, generated, published, cancelled
            $table->foreignId('blog_post_id')->nullable()->constrained('blog_posts')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_calendars');
    }
};
