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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('branch')->comment('지회명');
            $table->integer('year')->nullable()->comment('연도');
            $table->string('school_name')->comment('학교명');
            $table->boolean('is_member_school')->default(false)->comment('회원교 여부');
            $table->string('url')->nullable()->comment('URL');
            $table->timestamps();
            
            // 인덱스
            $table->index('branch');
            $table->index('year');
            $table->index('school_name');
            $table->index('is_member_school');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
