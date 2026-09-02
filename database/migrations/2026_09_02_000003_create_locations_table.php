<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table): void {
            $table->id();
            $table->string('name'); // e.g. Mumbai, Udaipur, Goa
            $table->string('slug')->unique()->index(); // e.g. wedding-photographer-mumbai
            $table->string('state')->nullable();
            $table->string('country')->default('India');
            $table->string('headline')->nullable();
            $table->text('intro')->nullable();
            $table->longText('content')->nullable();
            $table->string('hero_image')->nullable();
            $table->json('popular_venues')->nullable();
            $table->json('faqs')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
