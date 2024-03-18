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
        Schema::create('specimens', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->integer('logger_name_id');
            $table->string('type')->nullable();
            $table->string('location')->nullable();
            $table->string('uid');
            $table->boolean('imaged')->nullable();
            $table->string('imaging_approach')->nullable();
            $table->text('notes')->nullable();
            $table->integer('sample_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specimens');
    }
};
