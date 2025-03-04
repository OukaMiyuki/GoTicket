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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->string('invoice_number')->unique()->nullable();
            $table->unsignedBigInteger('operatorId')->nullable();
            $table->timestamp('transaction_timestamp');
            $table->timestamp('payment_timestamp')->nullable();
            $table->integer('qty');
            $table->string('price');
            $table->integer('tax')->nullable();
            $table->integer('discount')->nullable();
            $table->string('total_payment_amount')->nullable();
            $table->string('payment_method')->nullable();
            $table->boolean('payment_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
