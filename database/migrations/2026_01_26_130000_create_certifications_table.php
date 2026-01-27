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
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->string('level')->comment('구분: 1급 자격증 / 2급 자격증');
            $table->string('name')->comment('자격증명');
            $table->date('exam_date')->comment('시험일');
            $table->json('venue_category_ids')->nullable()->comment('시험장 코드관리 카테고리 ID 배열');
            $table->string('exam_method')->nullable()->comment('시험방식');
            $table->unsignedInteger('passing_score')->nullable()->comment('합격점수 기준');
            $table->text('eligibility')->nullable()->comment('응시자격');
            $table->text('content')->nullable()->comment('상세내용(에디터 HTML)');
            $table->string('thumbnail_path')->nullable()->comment('썸네일 경로');
            $table->boolean('is_public')->default(true)->comment('공개(true) / 비공개(false)');
            $table->dateTime('application_start')->nullable()->comment('신청기간 시작');
            $table->dateTime('application_end')->nullable()->comment('신청기간 종료');
            $table->unsignedInteger('capacity')->nullable()->comment('정원');
            $table->boolean('capacity_unlimited')->default(false)->comment('제한없음');
            $table->string('application_status')->comment('접수중 / 접수마감 / 접수예정 / 비공개');
            $table->json('payment_methods')->nullable()->comment('결제방법 배열');
            $table->text('deposit_account')->nullable()->comment('입금 계좌');
            $table->unsignedTinyInteger('deposit_deadline_days')->nullable()->comment('입금 기한(접수일 기준 n일, 1~7일)');
            $table->timestamps();

            $table->index('exam_date');
            $table->index('application_status');
            $table->index('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
