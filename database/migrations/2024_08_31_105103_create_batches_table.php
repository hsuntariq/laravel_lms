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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_no');
            $table->unsignedBigInteger('course_id'); // Make course_id unsignedBigInteger
            $table->unsignedBigInteger('teacher');
            $table->foreign('teacher')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['batch_no', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
