<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('education_applications', function (Blueprint $table) {
            $table->string('receipt_status', 20)->default('신청완료')->after('cancelled_at')
                ->comment('접수상태 (접수취소/신청완료/수료/미수료)');
        });

        $todayStart = now()->startOfDay()->format('Y-m-d');

        DB::table('education_applications')
            ->whereNotNull('cancelled_at')
            ->update(['receipt_status' => '접수취소']);

        DB::table('education_applications')
            ->whereNull('cancelled_at')
            ->where('is_completed', true)
            ->update(['receipt_status' => '수료']);

        DB::table('education_applications')
            ->join('educations', 'education_applications.education_id', '=', 'educations.id')
            ->whereNull('education_applications.cancelled_at')
            ->where('education_applications.is_completed', false)
            ->where('education_applications.receipt_status', '신청완료')
            ->where('educations.period_end', '<', $todayStart)
            ->update(['receipt_status' => '미수료']);

        DB::table('education_applications')
            ->join('online_educations', 'education_applications.online_education_id', '=', 'online_educations.id')
            ->whereNull('education_applications.cancelled_at')
            ->where('education_applications.is_completed', false)
            ->where('education_applications.receipt_status', '신청완료')
            ->where('online_educations.period_end', '<', $todayStart)
            ->update(['receipt_status' => '미수료']);

        DB::table('education_applications')
            ->join('seminar_trainings', 'education_applications.seminar_training_id', '=', 'seminar_trainings.id')
            ->whereNull('education_applications.cancelled_at')
            ->where('education_applications.is_completed', false)
            ->where('education_applications.receipt_status', '신청완료')
            ->where('seminar_trainings.period_end', '<', $todayStart)
            ->update(['receipt_status' => '미수료']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('education_applications', function (Blueprint $table) {
            $table->dropColumn('receipt_status');
        });
    }
};
