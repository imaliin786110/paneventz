<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terms_and_conditions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('version')->default(1);
            $table->unsignedInteger('advance_percentage')->default(50);
            $table->unsignedInteger('balance_percentage')->default(50);
            $table->string('balance_due')->default('Event Date');
            $table->boolean('advance_refundable')->default(false);
            $table->text('cancellation_policy')->nullable();
            $table->string('estimated_delivery_period')->default('1–2 months');
            $table->text('delivery_policy')->nullable();
            $table->string('extra_pendrive')->default('Chargeable');
            $table->string('extended_coverage_after')->default('12:30 AM');
            $table->string('late_night_transportation')->default('Chargeable');
            $table->string('hotel_coverage')->default('Additional');
            $table->string('home_coverage')->default('Additional');
            $table->string('extra_hours')->default('Chargeable');
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terms_and_conditions');
    }
};
