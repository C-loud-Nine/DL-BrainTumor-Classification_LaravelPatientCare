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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullabe();
            $table->string('email')->nullabe();
            $table->string('phone')->nullabe();
            $table->string('doctor')->nullabe();
            $table->string('date')->nullabe();
            $table->string('message')->nullabe();
            $table->string('status')->default('pending');
            $table->string('user_id')->nullabe();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
