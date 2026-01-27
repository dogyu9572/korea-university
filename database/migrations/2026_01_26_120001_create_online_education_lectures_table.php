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
        Schema::create('online_education_lectures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('education_program_id')->comment('교육 프로그램 ID');
            $table->string('lecture_name')->comment('강의명');
            $table->string('instructor_name')->comment('강사명');
            $table->unsignedInteger('lecture_time')->comment('강의시간 (시간 단위)');
            $table->unsignedInteger('order')->default(0)->comment('순서');
            $table->timestamps();

            $table->foreign('education_program_id')
                ->references('id')
                ->on('education_programs')
                ->onDelete('cascade');

            $table->index('education_program_id');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_education_lectures');
    }
};
