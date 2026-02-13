<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 온라인교육 강의와 강의영상(유튜브 URL) 연결을 위해 lecture_video_id 컬럼을 추가합니다.
     */
    public function up(): void
    {
        Schema::table('online_education_lectures', function (Blueprint $table) {
            $table->foreignId('lecture_video_id')->nullable()->after('online_education_id')
                ->constrained('lecture_videos')->onDelete('set null')
                ->comment('강의영상 ID (video_url 재생용)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('online_education_lectures', function (Blueprint $table) {
            $table->dropForeign(['lecture_video_id']);
        });
    }
};
