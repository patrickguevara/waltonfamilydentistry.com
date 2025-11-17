<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('office_info', function (Blueprint $table) {
            $table->id();
            $table->string('practice_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('monday_hours')->nullable();
            $table->string('tuesday_hours')->nullable();
            $table->string('wednesday_hours')->nullable();
            $table->string('thursday_hours')->nullable();
            $table->string('friday_hours')->nullable();
            $table->string('saturday_hours')->nullable();
            $table->string('sunday_hours')->nullable();
            $table->text('emergency_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('office_info');
    }
};
