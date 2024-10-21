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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('user_id'); // Foreign key for students
            $table->unsignedBigInteger('batch_no'); // Batch number
            $table->unsignedBigInteger('course_id'); // Foreign key for courses
            $table->date('attendance_date'); // The date of the attendance
            $table->enum('status', ['present', 'absent', 'leave']); // Attendance status
            $table->text('remarks')->nullable(); // Optional remarks
            $table->timestamps(); // Created at and updated at

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('batch_no')->references('id')->on('batches')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
