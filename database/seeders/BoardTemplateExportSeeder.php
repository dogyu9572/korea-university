<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: board_templates
 */
class BoardTemplateExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'board_templates';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'name' => '공지사항',
    'description' => '관리자가 작성하는 공지사항 게시판',
    'skin_id' => 1,
    'field_config' => '{"title": {"label": "제목", "enabled": true, "required": true}, "content": {"label": "내용", "enabled": true, "required": true}, "category": {"label": "카테고리", "enabled": false, "required": false}, "password": {"label": "비밀번호", "enabled": false, "required": false}, "is_active": {"label": "노출여부", "enabled": false, "required": false}, "is_secret": {"label": "비밀글", "enabled": false, "required": false}, "thumbnail": {"label": "썸네일", "enabled": false, "required": false}, "created_at": {"label": "등록일", "enabled": true, "required": false}, "attachments": {"label": "첨부파일", "enabled": true, "required": false}, "author_name": {"label": "작성자", "enabled": true, "required": true}}',
    'custom_fields_config' => NULL,
    'enable_notice' => 1,
    'enable_sorting' => 0,
    'enable_category' => 0,
    'category_id' => NULL,
    'is_single_page' => 0,
    'list_count' => 20,
    'permission_read' => 'all',
    'permission_write' => 'admin',
    'permission_comment' => 'member',
    'is_system' => 0,
    'is_active' => 1,
    'created_at' => '2026-01-20 15:00:16',
    'updated_at' => '2026-01-21 04:11:46',
  ),
  1 => 
  array (
    'id' => 2,
    'name' => '갤러리',
    'description' => '썸네일 이미지를 보여주는 갤러리형 게시판',
    'skin_id' => 1,
    'field_config' => '{"title": {"label": "제목", "enabled": true, "required": true}, "content": {"label": "내용", "enabled": true, "required": true}, "category": {"label": "카테고리", "enabled": true, "required": false}, "password": {"label": "비밀번호", "enabled": true, "required": false}, "is_secret": {"label": "비밀글", "enabled": false, "required": false}, "thumbnail": {"label": "썸네일", "enabled": true, "required": true}, "created_at": {"label": "등록일", "enabled": true, "required": false}, "attachments": {"label": "첨부파일", "enabled": true, "required": false}, "author_name": {"label": "작성자", "enabled": true, "required": true}}',
    'custom_fields_config' => NULL,
    'enable_notice' => 1,
    'enable_sorting' => 0,
    'enable_category' => 1,
    'category_id' => NULL,
    'is_single_page' => 0,
    'list_count' => 12,
    'permission_read' => 'all',
    'permission_write' => 'member',
    'permission_comment' => 'member',
    'is_system' => 0,
    'is_active' => 1,
    'created_at' => '2026-01-20 15:00:16',
    'updated_at' => '2026-01-20 15:00:16',
  ),
  2 => 
  array (
    'id' => 3,
    'name' => 'FAQ',
    'description' => '자주 묻는 질문 게시판',
    'skin_id' => 1,
    'field_config' => '{"title": {"label": "질문", "enabled": true, "required": true}, "content": {"label": "답변", "enabled": true, "required": true}, "category": {"label": "카테고리", "enabled": true, "required": false}, "password": {"label": "비밀번호", "enabled": false, "required": false}, "is_active": {"label": "노출여부", "enabled": false, "required": false}, "is_secret": {"label": "비밀글", "enabled": false, "required": false}, "thumbnail": {"label": "썸네일", "enabled": false, "required": false}, "created_at": {"label": "등록일", "enabled": true, "required": false}, "attachments": {"label": "첨부파일", "enabled": false, "required": false}, "author_name": {"label": "작성자", "enabled": true, "required": false}}',
    'custom_fields_config' => NULL,
    'enable_notice' => 0,
    'enable_sorting' => 0,
    'enable_category' => 1,
    'category_id' => 1,
    'is_single_page' => 0,
    'list_count' => 15,
    'permission_read' => 'all',
    'permission_write' => 'admin',
    'permission_comment' => 'member',
    'is_system' => 0,
    'is_active' => 1,
    'created_at' => '2026-01-20 15:00:16',
    'updated_at' => '2026-01-21 05:34:35',
  ),
  3 => 
  array (
    'id' => 5,
    'name' => '자료실',
    'description' => '관리자가 작성하는 공지사항 게시판',
    'skin_id' => 1,
    'field_config' => '{"title": {"label": "제목", "enabled": true, "required": true}, "content": {"label": "내용", "enabled": true, "required": true}, "category": {"label": "카테고리", "enabled": false, "required": false}, "password": {"label": "비밀번호", "enabled": false, "required": false}, "is_active": {"label": "노출여부", "enabled": false, "required": false}, "is_secret": {"label": "비밀글", "enabled": false, "required": false}, "thumbnail": {"label": "썸네일", "enabled": false, "required": false}, "created_at": {"label": "등록일", "enabled": true, "required": false}, "attachments": {"label": "첨부파일", "enabled": true, "required": false}, "author_name": {"label": "작성자", "enabled": true, "required": true}}',
    'custom_fields_config' => NULL,
    'enable_notice' => 1,
    'enable_sorting' => 0,
    'enable_category' => 0,
    'category_id' => NULL,
    'is_single_page' => 0,
    'list_count' => 20,
    'permission_read' => 'all',
    'permission_write' => 'admin',
    'permission_comment' => 'member',
    'is_system' => 0,
    'is_active' => 1,
    'created_at' => '2026-01-21 04:12:24',
    'updated_at' => '2026-01-30 01:37:16',
  ),
  4 => 
  array (
    'id' => 6,
    'name' => '정관 관리',
    'description' => NULL,
    'skin_id' => 1,
    'field_config' => '{"title": {"label": "제목", "enabled": false, "required": false}, "content": {"label": "내용", "enabled": true, "required": true}, "category": {"label": "카테고리", "enabled": false, "required": false}, "password": {"label": "비밀번호", "enabled": false, "required": false}, "is_active": {"label": "노출여부", "enabled": false, "required": false}, "is_secret": {"label": "비밀글", "enabled": false, "required": false}, "thumbnail": {"label": "썸네일", "enabled": false, "required": false}, "created_at": {"label": "등록일", "enabled": false, "required": false}, "attachments": {"label": "첨부파일", "enabled": false, "required": false}, "author_name": {"label": "작성자", "enabled": false, "required": false}}',
    'custom_fields_config' => NULL,
    'enable_notice' => 0,
    'enable_sorting' => 0,
    'enable_category' => 0,
    'category_id' => NULL,
    'is_single_page' => 1,
    'list_count' => 15,
    'permission_read' => 'all',
    'permission_write' => 'member',
    'permission_comment' => 'member',
    'is_system' => 0,
    'is_active' => 1,
    'created_at' => '2026-01-22 01:32:22',
    'updated_at' => '2026-01-22 01:32:22',
  ),
)
    ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table($table)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        foreach ($chunks as $chunk) {
            if (count($chunk) > 0) {
                DB::table($table)->insert($chunk);
            }
        }
    }
}