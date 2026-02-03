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
        Schema::create('online_educations', function (Blueprint $table) {
            $table->id();
            $table->string('education_class')->nullable()->comment('교육구분');
            $table->boolean('is_public')->default(true)->comment('공개(true) / 비공개(false)');
            $table->string('application_status')->comment('접수중 / 접수마감 / 접수예정 / 비공개');
            $table->string('name')->comment('교육명');
            $table->date('period_start')->nullable()->comment('교육 기간 시작');
            $table->date('period_end')->nullable()->comment('교육 기간 종료');
            $table->string('period_time')->nullable()->comment('교육 시간');
            $table->string('target')->nullable()->comment('교육 대상');
            $table->string('completion_criteria')->nullable()->comment('수료 기준');
            $table->string('survey_url')->nullable()->comment('설문조사 URL');
            $table->string('certificate_type')->default('이수증')->comment('이수증 / 수료증');
            $table->unsignedInteger('completion_hours')->nullable()->comment('교육 이수 시간');
            $table->dateTime('application_start')->nullable()->comment('신청 기간 시작');
            $table->dateTime('application_end')->nullable()->comment('신청 기간 종료');
            $table->unsignedInteger('capacity')->nullable()->comment('정원');
            $table->boolean('capacity_unlimited')->default(false)->comment('제한없음');
            $table->json('payment_methods')->nullable()->comment('결제방법 배열');
            $table->text('deposit_account')->nullable()->comment('입금 계좌');
            $table->unsignedInteger('deposit_deadline_days')->nullable()->comment('입금 기한(접수일 기준 n일)');
            $table->decimal('fee', 10, 2)->nullable()->comment('참가비');
            $table->boolean('is_free')->default(false)->comment('무료 여부');
            $table->string('thumbnail_path')->nullable()->comment('썸네일 경로');
            
            // 에디터 항목 6개
            $table->text('education_overview')->nullable()->comment('교육 개요');
            $table->text('education_schedule')->nullable()->comment('교육일정');
            $table->text('fee_info')->nullable()->comment('참가비 및 납부안내');
            $table->text('refund_policy')->nullable()->comment('취소 및 환불규정');
            $table->text('curriculum')->nullable()->comment('교과내용');
            $table->text('education_notice')->nullable()->comment('교육안내');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_educations');
    }
};
