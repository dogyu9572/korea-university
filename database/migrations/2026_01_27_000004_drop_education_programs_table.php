<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * education_programs 테이블을 삭제합니다.
     * education_schedules가 FK로 참조하므로 먼저 삭제합니다.
     */
    public function up(): void
    {
        Schema::dropIfExists('education_schedules');
        Schema::dropIfExists('education_programs');
    }

    public function down(): void
    {
        Schema::create('education_programs', function (Blueprint $table) {
            $table->id();
            $table->string('education_type');
            $table->string('education_class')->nullable();
            $table->boolean('is_public')->default(true);
            $table->string('application_status');
            $table->string('name');
            $table->timestamps();
        });
    }

};
