<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Make 'room' nullable
            $table->string('room')->nullable()->change();
            
            // Make 'appointment' nullable
            $table->string('appointment')->nullable()->change();
            
            // Make 'rating' nullable and default to 0
            $table->decimal('rating', 2, 1)->default(0)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Revert the changes in case of rollback
            $table->string('room')->nullable(false)->change();
            $table->string('appointment')->nullable(false)->change();
            $table->decimal('rating', 2, 1)->nullable(false)->default(0)->change();
        });
    }
    
};
