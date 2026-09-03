<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('content_items', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('type', 40)->index();
            $table->string('status', 24)->default('draft')->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->jsonb('content');
            $table->string('locale', 12)->default('en')->index();
            $table->foreignUuid('author_id')->constrained('users');
            $table->foreignUuid('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('publisher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestampTz('submitted_at')->nullable();
            $table->timestampTz('approved_at')->nullable();
            $table->timestampTz('published_at')->nullable()->index();
            $table->timestampTz('expires_at')->nullable()->index();
            $table->unsignedBigInteger('current_revision')->default(1);
            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['type', 'status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_items');
    }
};

