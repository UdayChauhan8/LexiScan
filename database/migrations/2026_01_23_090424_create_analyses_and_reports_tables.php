<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->longText('content_raw');
            $table->string('target_keyword')->nullable();
            
            // Metrics (Cached)
            $table->integer('word_count')->default(0);
            $table->integer('sentence_count')->default(0);
            $table->float('avg_sentence_length')->default(0);
            $table->float('keyword_density')->default(0);
            
            // Scores
            $table->float('readability_score')->default(0); // Flesch
            $table->integer('content_health_score')->default(0); // 0-100
            
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analysis_id')->constrained('analyses')->cascadeOnDelete();
            $table->uuid('public_link_token')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('analyses');
    }
};
