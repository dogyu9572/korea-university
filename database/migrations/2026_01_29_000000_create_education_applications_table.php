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
        Schema::create('education_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique()->comment('신청번호 (자동 생성)');
            $table->foreignId('education_program_id')->constrained('education_programs')->onDelete('cascade')->comment('교육 프로그램 ID');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade')->comment('신청자 회원 ID');
            $table->string('applicant_name')->comment('신청자명');
            $table->string('affiliation')->nullable()->comment('소속기관');
            $table->string('phone_number')->comment('휴대폰 번호');
            $table->string('email')->nullable()->comment('이메일');
            $table->dateTime('application_date')->comment('신청일시');
            $table->boolean('is_completed')->default(false)->comment('이수 여부');
            $table->string('certificate_number')->nullable()->comment('이수증/수료증 번호 (자동 생성)');
            $table->boolean('is_survey_completed')->default(false)->comment('설문조사 여부');
            $table->string('receipt_number')->nullable()->comment('영수증 번호 (자동 생성)');
            $table->string('refund_account_holder')->nullable()->comment('환불 계좌 예금주명');
            $table->string('refund_bank_name')->nullable()->comment('환불 계좌 은행명');
            $table->string('refund_account_number')->nullable()->comment('환불 계좌 번호');
            $table->decimal('participation_fee', 10, 2)->nullable()->comment('참가비');
            $table->string('fee_type')->nullable()->comment('참가비 타입 (회원교_2인1실, 비회원교_비숙박 등)');
            $table->json('payment_method')->nullable()->comment('결제방법 (무통장입금, 방문 카드결제, 온라인 카드결제)');
            $table->string('payment_status')->default('미입금')->comment('결제상태 (미입금, 입금완료)');
            $table->dateTime('payment_date')->nullable()->comment('입금일시');
            $table->string('tax_invoice_status')->default('미신청')->comment('세금계산서 발행여부 (미신청, 신청완료, 발행완료)');
            $table->boolean('has_cash_receipt')->default(false)->comment('현금영수증 발행 여부');
            $table->string('cash_receipt_purpose')->nullable()->comment('현금영수증 용도 (소득공제용, 사업자 지출증빙용)');
            $table->string('cash_receipt_number')->nullable()->comment('현금영수증 발행번호');
            $table->boolean('has_tax_invoice')->default(false)->comment('세금계산서 발행 여부');
            $table->string('company_name')->nullable()->comment('세금계산서 상호명');
            $table->string('registration_number')->nullable()->comment('세금계산서 등록번호');
            $table->string('contact_person_name')->nullable()->comment('세금계산서 담당자명');
            $table->string('contact_person_email')->nullable()->comment('세금계산서 담당자 이메일');
            $table->string('contact_person_phone')->nullable()->comment('세금계산서 담당자 휴대폰 번호');
            $table->timestamps();
            
            $table->index('education_program_id');
            $table->index('member_id');
            $table->index('application_number');
            $table->index('payment_status');
            $table->index('application_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_applications');
    }
};
