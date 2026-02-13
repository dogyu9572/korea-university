<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 진도율(페이지 체류시간) 계산을 위해 누적 수강 시간(분) 컬럼을 추가합니다.
     */
    public function up(): void
    {
        Schema::table('education_applications', function (Blueprint $table) {
            $table->unsignedInteger('learning_minutes')->default(0)->after('attendance_rate')
                ->comment('누적 수강 시간(분)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('education_applications', function (Blueprint $table) {
            $table->dropColumn('learning_minutes');
        });
    }
};
