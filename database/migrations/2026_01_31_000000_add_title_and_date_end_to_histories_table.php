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
        Schema::table('histories', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id')->comment('제목');
            $table->date('date_end')->nullable()->after('date')->comment('종료일 (미입력 시 단일일)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->dropColumn(['title', 'date_end']);
        });
    }
};
