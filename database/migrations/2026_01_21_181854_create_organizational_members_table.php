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
        Schema::create('organizational_members', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['회장', '부회장', '사무국', '지회', '감사', '고문']); // 분류
            $table->string('name'); // 이름
            $table->string('position')->nullable(); // 직위
            $table->string('affiliation')->nullable(); // 소속기관
            $table->string('phone')->nullable(); // 휴대폰 번호
            $table->integer('sort_order')->default(0); // 정렬 순서
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizational_members');
    }
};
