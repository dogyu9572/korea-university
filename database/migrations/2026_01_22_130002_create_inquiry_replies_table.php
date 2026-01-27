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
        Schema::create('inquiry_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiry_id')->constrained('inquiries')->onDelete('cascade')->comment('문의 ID');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null')->comment('답변 작성 관리자 ID');
            $table->string('author')->comment('작성자명');
            $table->text('content')->comment('답변 내용');
            $table->enum('status', ['답변대기', '답변완료'])->default('답변대기')->comment('답변 상태');
            $table->date('reply_date')->nullable()->comment('답변 등록일');
            $table->timestamps();
            
            // 인덱스
            $table->index('inquiry_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiry_replies');
    }
};
