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
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('related_type')->nullable()->after('action_url');
            $table->unsignedBigInteger('related_id')->nullable()->after('related_type');
            
            // Add index for better query performance
            $table->index(['related_type', 'related_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['related_type', 'related_id']);
            $table->dropColumn(['related_type', 'related_id']);
        });
    }
};