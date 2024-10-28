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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('topic');
            $table->text('description');
            $table->integer('max_marks');
            $table->unsignedBigInteger('batch_no');
            $table->foreign('batch_no')->references('id')->on('batches')->onDelete('cascade');
            $table->time('deadline');
            $table->string('type');
            $table->string('file');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
