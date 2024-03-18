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
        Schema::table('bio_samples', function (Blueprint $table) {
            $table->dropColumn(['location']);
        });

        Schema::table('samples', function (Blueprint $table) {
            $table->dropColumn(['location']);
        });

        Schema::table('specimens', function (Blueprint $table) {
            $table->dropColumn(['location']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
