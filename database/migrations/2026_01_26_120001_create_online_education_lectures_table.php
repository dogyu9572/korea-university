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
            $table->foreignId('online_education_id')->constrained('online_educations')->onDelete('cascade');
            $table->string('lecture_name')->comment('강의명');
            $table->string('instructor_name')->comment('강사명');
            $table->unsignedInteger('lecture_time')->comment('강의시간 (시간 단위)');
            $table->unsignedInteger('order')->default(0)->comment('순서');
            $table->timestamps();

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
