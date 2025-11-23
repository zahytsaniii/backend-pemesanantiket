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
        Schema::create('travel_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('departure_city')->default('Klaten');
            $table->string('destination');
            $table->timestamp('departure_datetime');
            $table->integer('quota');
            $table->decimal('price',12,2);
            $table->enum('category', ['reguler', 'vip'])->default('reguler');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_schedules');
    }
};
