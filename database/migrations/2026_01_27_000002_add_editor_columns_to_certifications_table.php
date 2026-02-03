<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * certifications 테이블에 에디터 컬럼(exam_overview, exam_trend, exam_venue)을 추가합니다.
     * 기존 content 컬럼이 있으면 exam_overview로 데이터 이관 후 제거합니다.
     */
    public function up(): void
    {
        if (Schema::hasColumn('certifications', 'content') && !Schema::hasColumn('certifications', 'exam_overview')) {
            Schema::table('certifications', function (Blueprint $table) {
                $table->text('exam_overview')->nullable()->after('eligibility')->comment('시험개요');
                $table->text('exam_trend')->nullable()->after('exam_overview')->comment('출제경향');
                $table->text('exam_venue')->nullable()->after('exam_trend')->comment('시험장 정보');
            });

            \DB::table('certifications')->update([
                'exam_overview' => \DB::raw('content'),
            ]);

            Schema::table('certifications', function (Blueprint $table) {
                $table->dropColumn('content');
            });
        } elseif (!Schema::hasColumn('certifications', 'exam_overview')) {
            Schema::table('certifications', function (Blueprint $table) {
                $table->text('exam_overview')->nullable()->after('eligibility')->comment('시험개요');
                $table->text('exam_trend')->nullable()->after('exam_overview')->comment('출제경향');
                $table->text('exam_venue')->nullable()->after('exam_trend')->comment('시험장 정보');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('certifications', 'exam_overview')) {
            Schema::table('certifications', function (Blueprint $table) {
                $table->dropColumn(['exam_overview', 'exam_trend', 'exam_venue']);
            });

            if (!Schema::hasColumn('certifications', 'content')) {
                Schema::table('certifications', function (Blueprint $table) {
                    $table->text('content')->nullable()->after('eligibility')->comment('상세내용(에디터 HTML)');
                });
            }
        }
    }
};
