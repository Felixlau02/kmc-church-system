<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    if (DB::getDriverName() !== 'sqlite') {
        DB::statement('ALTER DATABASE ' . env('DB_DATABASE') . ' CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        
        Schema::table('sermons', function (Blueprint $table) {
            DB::statement('ALTER DATABASE CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert
    }
};
