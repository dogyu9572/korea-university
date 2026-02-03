<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * online_education_lectures 테이블의 education_program_id를 online_education_id로 변경합니다.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('online_education_lectures', 'education_program_id')) {
            return;
        }

        Schema::table('online_education_lectures', function (Blueprint $table) {
            $table->dropForeign(['education_program_id']);
        });

        Schema::table('online_education_lectures', function (Blueprint $table) {
            $table->unsignedBigInteger('online_education_id')->nullable()->after('id');
        });

        \DB::table('online_education_lectures')->update([
            'online_education_id' => \DB::raw('education_program_id'),
        ]);

        Schema::table('online_education_lectures', function (Blueprint $table) {
            $table->dropColumn('education_program_id');
        });

        \DB::statement('ALTER TABLE online_education_lectures MODIFY online_education_id BIGINT UNSIGNED NOT NULL');

        // online_educations에 존재하지 않는 ID를 참조하는 고아 행 삭제
        \DB::table('online_education_lectures')
            ->whereNotIn('online_education_id', \DB::table('online_educations')->select('id'))
            ->delete();

        Schema::table('online_education_lectures', function (Blueprint $table) {
            $table->foreign('online_education_id')->references('id')->on('online_educations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('online_education_lectures', 'online_education_id')) {
            return;
        }

        Schema::table('online_education_lectures', function (Blueprint $table) {
            $table->dropForeign(['online_education_id']);
        });

        Schema::table('online_education_lectures', function (Blueprint $table) {
            $table->unsignedBigInteger('education_program_id')->nullable()->after('id');
        });

        \DB::table('online_education_lectures')->update([
            'education_program_id' => \DB::raw('online_education_id'),
        ]);

        Schema::table('online_education_lectures', function (Blueprint $table) {
            $table->dropColumn('online_education_id');
        });

        \DB::statement('ALTER TABLE online_education_lectures MODIFY education_program_id BIGINT UNSIGNED NOT NULL');

        Schema::table('online_education_lectures', function (Blueprint $table) {
            $table->foreign('education_program_id')->references('id')->on('education_programs')->onDelete('cascade');
        });
    }
};
