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
        Schema::create('inquiry_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiry_id')->constrained('inquiries')->onDelete('cascade')->comment('문의 ID');
            $table->string('file_path')->comment('파일 저장 경로');
            $table->string('file_name')->comment('원본 파일명');
            $table->integer('file_size')->comment('파일 크기');
            $table->timestamps();
            
            // 인덱스
            $table->index('inquiry_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiry_files');
    }
};
