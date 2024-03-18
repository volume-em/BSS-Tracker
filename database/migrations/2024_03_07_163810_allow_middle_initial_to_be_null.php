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
        Schema::table('investigators', function (Blueprint $table) {
            $table->string('middle_initial')->after('first_name')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('be_null', function (Blueprint $table) {
            $table->string('middle_initial')->after('first_name')->nullable(false)->change();
        });
    }
};
