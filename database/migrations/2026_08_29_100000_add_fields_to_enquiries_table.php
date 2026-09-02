<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('enquiries', 'name')) $table->string('name')->nullable();
            if (!Schema::hasColumn('enquiries', 'phone')) $table->string('phone')->nullable();
            if (!Schema::hasColumn('enquiries', 'email')) $table->string('email')->nullable();
            if (!Schema::hasColumn('enquiries', 'wedding_date')) $table->date('wedding_date')->nullable();
            if (!Schema::hasColumn('enquiries', 'wedding_location')) $table->string('wedding_location')->nullable();
            if (!Schema::hasColumn('enquiries', 'service')) $table->string('service')->nullable();
            if (!Schema::hasColumn('enquiries', 'message')) $table->text('message')->nullable();
            if (!Schema::hasColumn('enquiries', 'status')) $table->string('status')->default('new');
            if (!Schema::hasColumn('enquiries', 'admin_notes')) $table->text('admin_notes')->nullable();
        });
    }

    public function down(): void
    {
    }
};