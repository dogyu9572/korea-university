<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->timestamp('kakao_marketing_consent_at')->nullable()->after('kakao_marketing_consent')->comment('카카오 알림톡 수신동의 일시');
            $table->timestamp('email_marketing_consent_at')->nullable()->after('email_marketing_consent')->comment('이메일 수신동의 일시');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['kakao_marketing_consent_at', 'email_marketing_consent_at']);
        });
    }
};
