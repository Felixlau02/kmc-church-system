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
        // Drop the old broken attendances table if it exists
        if (Schema::hasTable('attendances')) {
            Schema::drop('attendances');
        }

        // Create a clean, consolidated attendances table
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('member_id');
            
            // Attendance data
            $table->string('status')->default('present'); // present, absent, late
            $table->date('attendance_date');
            $table->text('notes')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            
            // Indexes for better query performance
            $table->index('event_id');
            $table->index('member_id');
            $table->index('attendance_date');
            
            // Unique constraint to prevent duplicate attendance records
            $table->unique(['event_id', 'member_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};