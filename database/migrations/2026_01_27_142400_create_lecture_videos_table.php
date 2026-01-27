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
        Schema::create('lecture_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('강의 제목');
            $table->unsignedInteger('lecture_time')->comment('강의시간 (분)');
            $table->string('instructor_name')->comment('강사명');
            $table->string('video_url')->nullable()->comment('동영상 링크 (유튜브)');
            $table->string('thumbnail_path')->nullable()->comment('썸네일 이미지 경로');
            $table->boolean('is_active')->default(true)->comment('강의사용여부 (Y/N)');
            $table->timestamps();

            $table->index('is_active');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_videos');
    }
};
