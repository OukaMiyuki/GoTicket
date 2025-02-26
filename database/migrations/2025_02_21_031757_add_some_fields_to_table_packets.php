<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('packets', function (Blueprint $table) {
            $table->string('currency')->default('IDR')->after('price');
            $table->integer('max_people')->after('currency');
            $table->enum('validity_type', ['daily', 'weekly', 'monthly', 'yearly'])->after('duration');
            $table->boolean('status')->default(1)->after('packet_detail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('packets', function (Blueprint $table) {

        });
    }
};
