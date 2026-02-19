<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 세미나/해외연수 신청 시 참석자 요청사항 저장용
     */
    public function up(): void
    {
        Schema::table('education_applications', function (Blueprint $table) {
            $table->text('request_notes')->nullable()->after('contact_person_phone')->comment('세미나 신청 요청사항');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('education_applications', function (Blueprint $table) {
            $table->dropColumn('request_notes');
        });
    }
};
