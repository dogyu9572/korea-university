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
        Schema::create('education_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_id')->constrained('educations')->onDelete('cascade');
            $table->string('path')->comment('저장 경로');
            $table->string('name')->comment('원본 파일명');
            $table->unsignedInteger('order')->default(0)->comment('정렬');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_attachments');
    }
};
