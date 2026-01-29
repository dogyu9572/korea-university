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
        Schema::table('education_applications', function (Blueprint $table) {
            // 온라인교육 전용 필드
            $table->string('course_status', 20)->nullable()->after('is_completed')->comment('수강상태 (접수/승인/만료)');
            $table->decimal('attendance_rate', 5, 2)->nullable()->after('course_status')->comment('수강률 (%)');

            // 자격증 전용 필드
            $table->integer('score')->nullable()->after('attendance_rate')->comment('성적');
            $table->string('pass_status', 20)->nullable()->after('score')->comment('합격여부 (합격/불합격/미정)');
            $table->unsignedBigInteger('exam_venue_id')->nullable()->after('pass_status')->comment('시험장 ID');
            $table->string('exam_ticket_number', 50)->nullable()->after('exam_venue_id')->comment('수험표 번호');
            $table->string('qualification_certificate_number', 50)->nullable()->after('exam_ticket_number')->comment('자격확인서 번호');
            $table->string('pass_confirmation_number', 50)->nullable()->after('qualification_certificate_number')->comment('합격확인서 번호');
            $table->string('id_photo_path', 255)->nullable()->after('pass_confirmation_number')->comment('증명사진 경로');
            $table->date('birth_date')->nullable()->after('id_photo_path')->comment('생년월일');

            // 세미나/해외연수 전용 필드
            $table->unsignedBigInteger('roommate_member_id')->nullable()->after('birth_date')->comment('룸메이트 회원 ID');
            $table->string('roommate_name', 100)->nullable()->after('roommate_member_id')->comment('룸메이트 이름');
            $table->string('roommate_phone', 20)->nullable()->after('roommate_name')->comment('룸메이트 휴대폰');

            // 인덱스 추가
            $table->index('course_status');
            $table->index('pass_status');
            $table->index('exam_venue_id');
            $table->index('roommate_member_id');
        });

        // 외래키 제약조건 추가
        Schema::table('education_applications', function (Blueprint $table) {
            $table->foreign('exam_venue_id')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');

            $table->foreign('roommate_member_id')
                ->references('id')
                ->on('members')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('education_applications', function (Blueprint $table) {
            // 외래키 제약조건 제거
            $table->dropForeign(['exam_venue_id']);
            $table->dropForeign(['roommate_member_id']);

            // 인덱스 제거
            $table->dropIndex(['course_status']);
            $table->dropIndex(['pass_status']);
            $table->dropIndex(['exam_venue_id']);
            $table->dropIndex(['roommate_member_id']);

            // 컬럼 제거
            $table->dropColumn([
                'course_status',
                'attendance_rate',
                'score',
                'pass_status',
                'exam_venue_id',
                'exam_ticket_number',
                'qualification_certificate_number',
                'pass_confirmation_number',
                'id_photo_path',
                'birth_date',
                'roommate_member_id',
                'roommate_name',
                'roommate_phone',
            ]);
        });
    }
};
