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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade')->comment('회원 ID');
            $table->enum('category', ['교육', '자격증', '세미나', '해외연수', '기타'])->comment('분류');
            $table->string('title')->comment('문의 제목');
            $table->text('content')->comment('문의 내용');
            $table->enum('status', ['답변대기', '답변완료'])->default('답변대기')->comment('답변 상태');
            $table->integer('views')->default(0)->comment('조회수');
            $table->timestamps();
            
            // 인덱스
            $table->index('member_id');
            $table->index('category');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
