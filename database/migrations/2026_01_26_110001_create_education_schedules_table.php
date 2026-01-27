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
        Schema::create('education_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_program_id')->constrained('education_programs')->onDelete('cascade');
            $table->string('class_name')->comment('차수(예: 6차)');
            $table->date('schedule_start')->nullable()->comment('일정 시작');
            $table->date('schedule_end')->nullable()->comment('일정 종료');
            $table->string('location')->nullable()->comment('장소');
            $table->string('capacity')->nullable()->comment('인원(예: 40명)');
            $table->string('note')->nullable()->comment('비고');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_schedules');
    }
};
