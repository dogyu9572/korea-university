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
        Schema::table('education_programs', function (Blueprint $table) {
            $table->decimal('fee', 10, 2)->nullable()->after('thumbnail_path')->comment('교육비 (온라인 교육 전용)');
            $table->boolean('is_free')->default(false)->after('fee')->comment('무료 여부 (온라인 교육 전용)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('education_programs', function (Blueprint $table) {
            $table->dropColumn(['fee', 'is_free']);
        });
    }
};
