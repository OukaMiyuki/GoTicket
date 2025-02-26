<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('payment_status_detail', ['pending', 'paid', 'cancelled'])->default('pending')->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
};
