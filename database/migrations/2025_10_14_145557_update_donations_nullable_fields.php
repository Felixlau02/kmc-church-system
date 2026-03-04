<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('donations', function (Blueprint $table) {
        $table->string('donor_name')->nullable()->change();
        $table->string('email')->nullable()->change();
        $table->decimal('amount', 10, 2)->nullable()->change();
        $table->string('payment_method')->nullable()->change();
    });
}

public function down()
{
    Schema::table('donations', function (Blueprint $table) {
        $table->string('donor_name')->nullable(false)->change();
        $table->string('email')->nullable(false)->change();
        $table->decimal('amount', 10, 2)->nullable(false)->change();
        $table->string('payment_method')->nullable(false)->change();
    });
}

};
