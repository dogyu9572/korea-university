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
        Schema::table('seminar_trainings', function (Blueprint $table) {
            $table->string('total_sessions_class')->nullable()->after('education_class')->comment('총차시/기수');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seminar_trainings', function (Blueprint $table) {
            $table->dropColumn('total_sessions_class');
        });
    }
};
