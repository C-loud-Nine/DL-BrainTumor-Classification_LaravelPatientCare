<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('repverdict', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');  // Link to the reports table
            $table->enum('verdict', ['Yes', 'No'])->nullable();  // Doctor's verdict
            $table->timestamps();
    
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('repverdict');
    }
    
};
