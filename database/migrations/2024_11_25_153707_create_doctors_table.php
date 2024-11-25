<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->string('specialization');  // Changed from 'speciality' to 'specialization'
            $table->string('room')->nullable();  // Made room nullable
            $table->string('appointment')->nullable();  // Made appointment nullable
            $table->decimal('rating', 2, 1)->default(0)->nullable();  // Rating with default value and nullable
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}
