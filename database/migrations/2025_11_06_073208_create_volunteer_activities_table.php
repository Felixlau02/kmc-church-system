<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_activities', function (Blueprint $table) {
            $table->id();
            $table->string('activity_name');
            $table->text('description')->nullable();
            $table->date('activity_date');
            $table->time('activity_time')->nullable();
            $table->string('status')->default('draft'); 
            $table->timestamps();
            
            $table->index('activity_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_activities');
    }
};