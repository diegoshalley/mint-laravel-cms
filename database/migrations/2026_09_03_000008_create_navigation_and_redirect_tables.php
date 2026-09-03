<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('navigation_items', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('location', 30)->default('primary')->index();
            $table->foreignUuid('parent_id')->nullable()->constrained('navigation_items')->cascadeOnDelete();
            $table->string('label', 100);
            $table->string('destination', 500);
            $table->unsignedSmallInteger('position')->default(0);
            $table->boolean('is_visible')->default(true)->index();
            $table->boolean('open_in_new_tab')->default(false);
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestampsTz();
            $table->index(['location', 'position']);
        });

        Schema::create('redirects', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('source_path', 500)->unique();
            $table->string('destination_path', 500);
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedBigInteger('hit_count')->default(0);
            $table->timestampTz('last_hit_at')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
        Schema::dropIfExists('navigation_items');
    }
};
