<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_no');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('teacher');
            $table->string('branch');
            $table->json('days');
            $table->json('class_links');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
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
