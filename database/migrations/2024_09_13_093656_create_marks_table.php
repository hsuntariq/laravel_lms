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
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  
            $table->unsignedBigInteger('assignment_id');
            $table->unsignedBigInteger('answer_id');
            $table->unsignedBigInteger('max_marks');
            $table->integer('obt_marks');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('assignment_id')->references('id')->on('assignments');
            $table->foreign('answer_id')->references('id')->on('answers');
            $table->string('comments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
