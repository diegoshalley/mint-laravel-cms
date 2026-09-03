<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('content_revisions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('content_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('revision');
            $table->jsonb('snapshot');
            $table->foreignUuid('created_by')->constrained('users');
            $table->text('change_note')->nullable();
            $table->timestampTz('created_at')->useCurrent();

            $table->unique(['content_item_id', 'revision']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_revisions');
    }
};

