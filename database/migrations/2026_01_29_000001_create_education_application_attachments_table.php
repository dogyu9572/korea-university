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
        Schema::create('education_application_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_application_id')->constrained('education_applications')->onDelete('cascade')->comment('교육 신청 ID');
            $table->string('path')->comment('저장 경로');
            $table->string('name')->comment('원본 파일명');
            $table->string('type')->default('business_registration')->comment('파일 타입 (business_registration: 사업자등록증)');
            $table->unsignedInteger('order')->default(0)->comment('정렬');
            $table->timestamps();
            
            $table->index('education_application_id');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_application_attachments');
    }
};
