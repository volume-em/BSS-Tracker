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
        Schema::create('bio_samples', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->string('location')->nullable();
            $table->string('label');
            $table->boolean('exhausted')->nullable();
            $table->integer('logger_name_id');
            $table->integer('project_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bio_samples');
    }
};
