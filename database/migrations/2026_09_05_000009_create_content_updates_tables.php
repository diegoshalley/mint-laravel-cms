<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('content_updates', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('content_item_id')->constrained()->cascadeOnDelete();
            $table->string('status', 24)->default('draft')->index();
            $table->jsonb('proposed');
            $table->unsignedBigInteger('revision')->default(1);
            $table->foreignUuid('author_id')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('publisher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestampTz('submitted_at')->nullable();
            $table->timestampTz('approved_at')->nullable();
            $table->timestampTz('effective_at')->nullable()->index();
            $table->timestampTz('applied_at')->nullable();
            $table->timestampsTz();
            $table->index(['content_item_id', 'status']);
        });

        Schema::create('content_update_revisions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('content_update_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('revision');
            $table->jsonb('proposed');
            $table->foreignUuid('created_by')->constrained('users')->restrictOnDelete();
            $table->text('change_note');
            $table->timestampTz('created_at')->useCurrent();
            $table->unique(['content_update_id', 'revision']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_update_revisions');
        Schema::dropIfExists('content_updates');
    }
};
