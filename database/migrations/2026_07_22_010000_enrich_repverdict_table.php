<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * A doctor's review is more than a yes/no. This adds the clinical detail that
 * makes a verdict auditable: who signed it off, whether they'd reclassify the
 * scan, how sure they are, and free-text reasoning.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Widen the verdict beyond Yes/No.
        DB::statement("ALTER TABLE repverdict MODIFY verdict ENUM('Yes','No','Uncertain') NULL");

        Schema::table('repverdict', function (Blueprint $table) {
            if (!Schema::hasColumn('repverdict', 'corrected_class')) {
                $table->string('corrected_class')->nullable()->after('verdict');
            }
            if (!Schema::hasColumn('repverdict', 'certainty')) {
                $table->string('certainty')->nullable()->after('corrected_class');
            }
            if (!Schema::hasColumn('repverdict', 'notes')) {
                $table->text('notes')->nullable()->after('certainty');
            }
            if (!Schema::hasColumn('repverdict', 'reviewed_by')) {
                $table->string('reviewed_by')->nullable()->after('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('repverdict', function (Blueprint $table) {
            foreach (['corrected_class', 'certainty', 'notes', 'reviewed_by'] as $col) {
                if (Schema::hasColumn('repverdict', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        DB::statement("ALTER TABLE repverdict MODIFY verdict ENUM('Yes','No') NULL");
    }
};
