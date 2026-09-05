<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('editorial_comments', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('content_item_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('author_id')->constrained('users');
            $table->string('kind', 32)->default('comment')->index();
            $table->text('body');
            $table->timestampsTz();
            $table->index(['content_item_id', 'created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('editorial_comments'); }
};
