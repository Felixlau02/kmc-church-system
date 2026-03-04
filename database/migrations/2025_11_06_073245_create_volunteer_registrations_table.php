<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')
                  ->constrained('volunteer_activities')
                  ->onDelete('cascade');
            $table->foreignId('team_id')
                  ->constrained('volunteer_teams')
                  ->onDelete('cascade');
            $table->foreignId('member_id')
                  ->constrained('members')
                  ->onDelete('cascade');
            $table->string('role');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Prevent duplicate member in same team
            $table->unique(['team_id', 'member_id']);
            
            $table->index('activity_id');
            $table->index('member_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_registrations');
    }
};