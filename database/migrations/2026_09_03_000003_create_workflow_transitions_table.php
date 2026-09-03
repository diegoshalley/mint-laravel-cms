<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('workflow_transitions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('content_item_id')->constrained()->cascadeOnDelete();
            $table->string('from_status', 24);
            $table->string('to_status', 24);
            $table->foreignUuid('performed_by')->constrained('users');
            $table->text('reason')->nullable();
            $table->jsonb('context')->nullable();
            $table->timestampTz('created_at')->useCurrent();

            $table->index(['content_item_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_transitions');
    }
};
