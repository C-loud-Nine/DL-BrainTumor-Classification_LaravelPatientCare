<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDoctorsTableForRatings extends Migration
{
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Drop the existing 'rating' column
            $table->dropColumn('rating');
        });

        Schema::table('doctors', function (Blueprint $table) {
            // Re-add the 'rating' column with the new precision
            $table->decimal('rating', 3, 2)->default(0.00)->after('appointment');
            
            // Add 'rating_count' column
            $table->unsignedInteger('rating_count')->default(0)->after('rating');
        });
    }

    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Drop the new columns
            $table->dropColumn('rating_count');
            $table->dropColumn('rating');
        });

        Schema::table('doctors', function (Blueprint $table) {
            // Re-add the original 'rating' column
            $table->decimal('rating', 2, 1)->default(0)->nullable()->after('appointment');
        });
    }
}
