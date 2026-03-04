<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sermons', function (Blueprint $table) {
            // Add new columns only if they don't already exist
            if (!Schema::hasColumn('sermons', 'video_path')) {
                $table->string('video_path')->nullable();
            }

            if (!Schema::hasColumn('sermons', 'video_thumbnail')) {
                $table->string('video_thumbnail')->nullable();
            }

            if (!Schema::hasColumn('sermons', 'video_duration')) {
                $table->integer('video_duration')->nullable();
            }

            if (!Schema::hasColumn('sermons', 'ai_transcript')) {
                $table->longText('ai_transcript')->nullable();
            }

            if (!Schema::hasColumn('sermons', 'ai_summary')) {
                $table->text('ai_summary')->nullable();
            }

            if (!Schema::hasColumn('sermons', 'ai_key_points')) {
                $table->json('ai_key_points')->nullable();
            }

            if (!Schema::hasColumn('sermons', 'ai_translations')) {
                $table->json('ai_translations')->nullable();
            }

            if (!Schema::hasColumn('sermons', 'processing_status')) {
                $table->enum('processing_status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            }

            if (!Schema::hasColumn('sermons', 'processing_error')) {
                $table->text('processing_error')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('sermons', function (Blueprint $table) {
            $table->dropColumn([
                'video_path',
                'video_thumbnail',
                'video_duration',
                'ai_transcript',
                'ai_summary',
                'ai_key_points',
                'ai_translations',
                'processing_status',
                'processing_error',
            ]);
        });
    }
};
