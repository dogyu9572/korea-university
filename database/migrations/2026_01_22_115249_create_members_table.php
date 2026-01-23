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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->enum('join_type', ['email', 'kakao', 'naver'])->comment('가입 구분');
            $table->string('email')->nullable()->unique()->comment('이메일 (소셜 로그인 시 nullable)');
            $table->string('login_id')->nullable()->unique()->comment('로그인 ID (이메일 가입 시 email과 동일)');
            $table->string('password')->nullable()->comment('비밀번호 (소셜 로그인 시 nullable)');
            $table->string('name')->comment('이름');
            $table->string('phone_number')->unique()->comment('휴대폰번호');
            $table->string('birth_date', 8)->nullable()->comment('생년월일 (YYYYMMDD 형식)');
            $table->string('address_postcode')->nullable()->comment('우편번호');
            $table->string('address_base')->nullable()->comment('기본주소');
            $table->string('address_detail')->nullable()->comment('상세주소');
            $table->string('school_name')->comment('학교명');
            $table->boolean('is_school_representative')->default(false)->comment('학교 대표자 여부');
            $table->boolean('email_marketing_consent')->default(false)->comment('이메일 수신동의');
            $table->boolean('kakao_marketing_consent')->default(false)->comment('카카오 알림톡 수신동의');
            $table->boolean('sms_marketing_consent')->default(false)->comment('SMS 수신동의');
            $table->timestamp('terms_agreed_at')->nullable()->comment('약관 동의 일시');
            $table->timestamp('withdrawn_at')->nullable()->comment('탈퇴 일시');
            $table->timestamps();
            
            // 인덱스
            $table->index('name');
            $table->index('school_name');
            $table->index('join_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
