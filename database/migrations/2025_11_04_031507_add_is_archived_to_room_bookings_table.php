<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsArchivedToRoomBookingsTable extends Migration
{
    public function up()
    {
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->after('status');
        });
    }

    public function down()
    {
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->dropColumn('is_archived');
        });
    }
}