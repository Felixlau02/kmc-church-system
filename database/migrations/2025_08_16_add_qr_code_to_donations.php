<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('qr_code_path')->nullable()->after('payment_method');
            $table->string('qr_code_label')->nullable()->after('qr_code_path');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['qr_code_path', 'qr_code_label']);
        });
    }
};