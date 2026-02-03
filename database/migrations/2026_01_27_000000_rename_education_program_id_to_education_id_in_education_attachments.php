<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * education_attachments 테이블의 education_program_id 컬럼을 education_id로 변경합니다.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('education_attachments', 'education_program_id')) {
            return;
        }

        Schema::table('education_attachments', function (Blueprint $table) {
            $table->dropForeign(['education_program_id']);
        });

        Schema::table('education_attachments', function (Blueprint $table) {
            $table->unsignedBigInteger('education_id')->nullable()->after('id');
        });

        \DB::table('education_attachments')->update([
            'education_id' => \DB::raw('education_program_id'),
        ]);

        Schema::table('education_attachments', function (Blueprint $table) {
            $table->dropColumn('education_program_id');
        });

        \DB::statement('ALTER TABLE education_attachments MODIFY education_id BIGINT UNSIGNED NOT NULL');

        Schema::table('education_attachments', function (Blueprint $table) {
            $table->foreign('education_id')->references('id')->on('educations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('education_attachments', 'education_id')) {
            return;
        }

        Schema::table('education_attachments', function (Blueprint $table) {
            $table->dropForeign(['education_id']);
        });

        Schema::table('education_attachments', function (Blueprint $table) {
            $table->unsignedBigInteger('education_program_id')->nullable()->after('id');
        });

        \DB::table('education_attachments')->update([
            'education_program_id' => \DB::raw('education_id'),
        ]);

        Schema::table('education_attachments', function (Blueprint $table) {
            $table->dropColumn('education_id');
        });

        \DB::statement('ALTER TABLE education_attachments MODIFY education_program_id BIGINT UNSIGNED NOT NULL');

        Schema::table('education_attachments', function (Blueprint $table) {
            $table->foreign('education_program_id')->references('id')->on('education_programs')->onDelete('cascade');
        });
    }
};
