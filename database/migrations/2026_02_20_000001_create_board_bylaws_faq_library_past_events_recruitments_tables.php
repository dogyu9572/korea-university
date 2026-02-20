<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 게시판 만들기로 추가된 보드 테이블 5개 (board_notices와 동일 스키마)
 */
return new class extends Migration
{
    private function boardPostSchema(Blueprint $table): void
    {
        $table->id();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->string('title');
        $table->text('content');
        $table->string('author_name');
        $table->string('password')->nullable();
        $table->boolean('is_notice')->default(false);
        $table->boolean('is_secret')->default(false);
        $table->boolean('is_active')->default(true);
        $table->string('category')->nullable();
        $table->json('attachments')->nullable();
        $table->integer('view_count')->default(0);
        $table->integer('sort_order')->default(0);
        $table->json('custom_fields')->nullable();
        $table->string('thumbnail')->nullable();
        $table->timestamps();
        $table->softDeletes();
        $table->index(['is_notice', 'created_at']);
        $table->index(['category', 'created_at']);
        $table->index(['user_id', 'created_at']);
        $table->index('thumbnail');
        $table->index('sort_order');
    }

    public function up(): void
    {
        Schema::create('board_bylaws', function (Blueprint $table) {
            $this->boardPostSchema($table);
        });
        Schema::create('board_faq', function (Blueprint $table) {
            $this->boardPostSchema($table);
        });
        Schema::create('board_library', function (Blueprint $table) {
            $this->boardPostSchema($table);
        });
        Schema::create('board_past_events', function (Blueprint $table) {
            $this->boardPostSchema($table);
        });
        Schema::create('board_recruitments', function (Blueprint $table) {
            $this->boardPostSchema($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('board_recruitments');
        Schema::dropIfExists('board_past_events');
        Schema::dropIfExists('board_library');
        Schema::dropIfExists('board_faq');
        Schema::dropIfExists('board_bylaws');
    }
};
