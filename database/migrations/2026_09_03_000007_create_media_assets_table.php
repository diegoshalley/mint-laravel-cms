<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media_assets', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('original_name');
            $table->string('kind', 20)->index();
            $table->string('extension', 12);
            $table->string('mime_type', 150);
            $table->unsignedBigInteger('size_bytes');
            $table->char('sha256', 64)->index();
            $table->string('disk', 40)->default('quarantine');
            $table->string('path');
            $table->string('title');
            $table->text('alt_text')->nullable();
            $table->boolean('is_decorative')->default(false);
            $table->text('description')->nullable();
            $table->string('credit')->nullable();
            $table->string('language', 10)->default('en');
            $table->text('accessibility_notes')->nullable();
            $table->string('scan_status', 20)->default('pending')->index();
            $table->text('scan_message')->nullable();
            $table->timestampTz('scanned_at')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->foreignUuid('uploaded_by')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestampTz('approved_at')->nullable();
            $table->timestampTz('retired_at')->nullable();
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_assets');
    }
};
