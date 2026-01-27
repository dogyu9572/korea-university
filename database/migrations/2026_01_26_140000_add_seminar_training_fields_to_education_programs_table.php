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
            $table->decimal('annual_fee', 10, 2)->nullable()->after('completion_hours')->comment('연회비 (세미나/해외연수 전용)');
            $table->unsignedInteger('capacity_per_school')->nullable()->after('capacity_unlimited')->comment('회원교당 정원 (세미나/해외연수 전용)');
            $table->boolean('capacity_per_school_unlimited')->default(false)->after('capacity_per_school')->comment('회원교당 정원 제한없음 (세미나/해외연수 전용)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('education_programs', function (Blueprint $table) {
            $table->dropColumn(['annual_fee', 'capacity_per_school', 'capacity_per_school_unlimited']);
        });
    }
};
