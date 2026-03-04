<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')
                  ->constrained('volunteer_activities')
                  ->onDelete('cascade');
            $table->string('team_name');
            $table->string('team_type'); // worship, usher, technical, other
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['activity_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_teams');
    }
};