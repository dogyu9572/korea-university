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
        Schema::create('lecture_video_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecture_video_id')->constrained('lecture_videos')->onDelete('cascade');
            $table->string('path')->comment('저장 경로');
            $table->string('name')->comment('원본 파일명');
            $table->unsignedInteger('order')->default(0)->comment('정렬');
            $table->timestamps();

            $table->index('lecture_video_id');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_video_attachments');
    }
};
