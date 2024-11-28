<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('scanner_name');  // The name of the scanner (or the user who is performing the scan)
            $table->string('scanner_id');    // The ID of the scanner (or the user who is performing the scan)
            $table->string('user_name');     // The name of the person whose report it is
            $table->string('user_id');       // The ID of the person whose report it is
            $table->string('report_class');  // The class predicted by the model (e.g., 'normal', 'abnormal')
            $table->decimal('confidence', 5, 2); // Confidence in prediction (e.g., 85.50)
            $table->string('report_image'); // URL or path to the uploaded report image
            $table->string('type');// Add the 'type' field, with a default value of 'user'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
