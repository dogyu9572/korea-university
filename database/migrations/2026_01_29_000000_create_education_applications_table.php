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
            $table->foreignId('education_id')->nullable()->constrained('educations')->onDelete('cascade')->comment('교육 ID (정기/수시)');
            $table->foreignId('online_education_id')->nullable()->constrained('online_educations')->onDelete('cascade')->comment('온라인교육 ID');
            $table->foreignId('certification_id')->nullable()->constrained('certifications')->onDelete('cascade')->comment('자격증 ID');
            $table->foreignId('seminar_training_id')->nullable()->constrained('seminar_trainings')->onDelete('cascade')->comment('세미나/해외연수 ID');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade')->comment('신청자 회원 ID');
            $table->string('applicant_name')->comment('신청자명');
            $table->string('affiliation')->nullable()->comment('소속기관');
            $table->string('phone_number')->comment('휴대폰 번호');
            $table->string('email')->nullable()->comment('이메일');
            $table->dateTime('application_date')->comment('신청일시');
            $table->boolean('is_completed')->default(false)->comment('이수 여부');
            $table->string('course_status', 20)->nullable()->comment('수강상태 (접수/승인/만료)');
            $table->decimal('attendance_rate', 5, 2)->nullable()->comment('수강률 (%)');
            $table->integer('score')->nullable()->comment('성적');
            $table->string('pass_status', 20)->nullable()->comment('합격여부 (합격/불합격/미정)');
            $table->unsignedBigInteger('exam_venue_id')->nullable()->comment('시험장 ID');
            $table->string('exam_ticket_number', 50)->nullable()->comment('수험표 번호');
            $table->string('qualification_certificate_number', 50)->nullable()->comment('자격확인서 번호');
            $table->string('pass_confirmation_number', 50)->nullable()->comment('합격확인서 번호');
            $table->string('id_photo_path', 255)->nullable()->comment('증명사진 경로');
            $table->date('birth_date')->nullable()->comment('생년월일');
            $table->unsignedBigInteger('roommate_member_id')->nullable()->comment('룸메이트 회원 ID');
            $table->string('roommate_name', 100)->nullable()->comment('룸메이트 이름');
            $table->string('roommate_phone', 20)->nullable()->comment('룸메이트 휴대폰');
            $table->string('certificate_number')->nullable()->comment('이수증/수료증 번호 (자동 생성)');
            $table->boolean('is_survey_completed')->default(false)->comment('설문조사 여부');
            $table->string('receipt_number')->nullable()->comment('영수증 번호 (자동 생성)');
            $table->string('refund_account_holder')->nullable()->comment('환불 계좌 예금주명');
            $table->string('refund_bank_name')->nullable()->comment('환불 계좌 은행명');
            $table->string('refund_account_number')->nullable()->comment('환불 계좌 번호');
            $table->decimal('participation_fee', 10, 2)->nullable()->comment('참가비');
            $table->string('fee_type')->nullable()->comment('참가비 타입');
            $table->json('payment_method')->nullable()->comment('결제방법');
            $table->string('payment_status')->default('미입금')->comment('결제상태 (미입금, 입금완료)');
            $table->dateTime('payment_date')->nullable()->comment('입금일시');
            $table->string('tax_invoice_status')->default('미신청')->comment('세금계산서 발행여부');
            $table->boolean('has_cash_receipt')->default(false)->comment('현금영수증 발행 여부');
            $table->string('cash_receipt_purpose')->nullable()->comment('현금영수증 용도');
            $table->string('cash_receipt_number')->nullable()->comment('현금영수증 발행번호');
            $table->boolean('has_tax_invoice')->default(false)->comment('세금계산서 발행 여부');
            $table->string('company_name')->nullable()->comment('세금계산서 상호명');
            $table->string('registration_number')->nullable()->comment('세금계산서 등록번호');
            $table->string('contact_person_name')->nullable()->comment('세금계산서 담당자명');
            $table->string('contact_person_email')->nullable()->comment('세금계산서 담당자 이메일');
            $table->string('contact_person_phone')->nullable()->comment('세금계산서 담당자 휴대폰 번호');
            $table->timestamps();

            $table->index('education_id');
            $table->index('online_education_id');
            $table->index('certification_id');
            $table->index('seminar_training_id');
            $table->index('member_id');
            $table->index('application_number');
            $table->index('payment_status');
            $table->index('application_date');
            $table->index('course_status');
            $table->index('pass_status');
            $table->index('exam_venue_id');
            $table->index('roommate_member_id');

            $table->foreign('exam_venue_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('roommate_member_id')->references('id')->on('members')->onDelete('set null');
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
