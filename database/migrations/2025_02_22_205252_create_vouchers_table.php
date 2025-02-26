<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->string('code')->unique();
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->string('discount_value');
            $table->string('min_spend')->nullable();
            $table->string('max_discount')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('usage_limit')->default(1);
            $table->integer('per_user_limit')->default(1);
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('vouchers');
    }
};
