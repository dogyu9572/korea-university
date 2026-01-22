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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // 날짜
            $table->integer('year'); // 연도 (date에서 추출, 검색용)
            $table->text('content'); // 내용
            $table->enum('is_visible', ['Y', 'N'])->default('Y'); // 노출여부
            $table->timestamps();
            
            $table->index('year');
            $table->index('date');
            $table->index('is_visible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
