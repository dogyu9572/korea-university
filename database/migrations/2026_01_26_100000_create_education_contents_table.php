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
        Schema::create('education_contents', function (Blueprint $table) {
            $table->id();
            $table->text('education_guide')->nullable()->comment('교육 안내');
            $table->text('certification_guide')->nullable()->comment('자격증 안내');
            $table->text('expert_level_1')->nullable()->comment('대학연구행정전문가 1급');
            $table->text('expert_level_2')->nullable()->comment('대학연구행정전문가 2급');
            $table->text('seminar_guide')->nullable()->comment('세미나 안내');
            $table->text('overseas_training_guide')->nullable()->comment('해외연수 안내');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_contents');
    }
};
