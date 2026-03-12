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
        Schema::table('doctors', function (Blueprint $table) {
            // Make image column nullable
            $table->string('image')->nullable()->change();

            // Rename descreption to description
            if (Schema::hasColumn('doctors', 'descreption')) {
                $table->renameColumn('descreption', 'description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Make image column not nullable
            $table->string('image')->nullable(false)->change();

            // Rename back
            if (Schema::hasColumn('doctors', 'description')) {
                $table->renameColumn('description', 'descreption');
            }
        });
    }
};
