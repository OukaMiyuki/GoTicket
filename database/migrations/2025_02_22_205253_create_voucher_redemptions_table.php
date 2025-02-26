<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('voucher_redemptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('voucherId');
            $table->unsignedBigInteger('invoiceId')->nullable();
            $table->dateTime('redeemed_at')->nullable();
            $table->enum('status', ['pending', 'successful', 'failed'])->default('pending');
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('voucherId')->references('id')->on('vouchers')->onDelete('cascade');
            $table->foreign('invoiceId')->references('id')->on('invoices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('voucher_redemptions');
    }
};
