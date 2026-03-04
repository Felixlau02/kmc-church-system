<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('booking_time');
            $table->time('end_time')->nullable()->after('start_time');
        });
    }

    public function down()
    {
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
        });
    }
};