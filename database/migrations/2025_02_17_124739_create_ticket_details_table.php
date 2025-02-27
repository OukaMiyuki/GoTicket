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
        Schema::create('ticket_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticketId')->unique();
            $table->string('ticket_unique_id')->unique();
            $table->string('owner_name')->nullable();
            $table->string('id_number')->nullable();
            $table->string('owner_phone_number')->nullable();
            $table->string('owner_email_address')->nullable();
            $table->string('owner_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_details');
    }
};
