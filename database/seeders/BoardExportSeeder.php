<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: boards
 */
class BoardExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'boards';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'name' => '공지사항',
    'slug' => 'notices',
    'description' => NULL,
    'skin_id' => 1,
    'template_id' => 1,
    'is_active' => 1,
    'table_created' => 0,
    'list_count' => 20,
    'enable_notice' => 1,
    'is_single_page' => 0,
    'enable_sorting' => 0,
    'hot_threshold' => 100,
    'permission_read' => 'all',
    'permission_write' => 'admin',
    'permission_comment' => 'member',
    'created_at' => '2025-09-23 06:55:06',
    'updated_at' => '2026-01-21 04:11:46',
    'deleted_at' => NULL,
    'field_config' => '{"title": {"label": "제목", "enabled": true, "required": true}, "content": {"label": "내용", "enabled": true, "required": true}, "category": {"label": "카테고리", "enabled": false, "required": false}, "password": {"label": "비밀번호", "enabled": false, "required": false}, "is_active": {"label": "노출여부", "enabled": false, "required": false}, "is_secret": {"label": "비밀글", "enabled": false, "required": false}, "thumbnail": {"label": "썸네일", "enabled": false, "required": false}, "created_at": {"label": "등록일", "enabled": true, "required": false}, "attachments": {"label": "첨부파일", "enabled": true, "required": false}, "author_name": {"label": "작성자", "enabled": true, "required": true}}',
    'custom_fields_config' => '[]',
  ),
  1 => 
  array (
    'id' => 2,
    'name' => '갤러리',
    'slug' => 'gallerys',
    'description' => NULL,
    'skin_id' => 2,
    'template_id' => NULL,
    'is_active' => 1,
    'table_created' => 0,
    'list_count' => 10,
    'enable_notice' => 0,
    'is_single_page' => 0,
    'enable_sorting' => 0,
    'hot_threshold' => 100,
    'permission_read' => 'all',
    'permission_write' => 'all',
    'permission_comment' => 'all',
    'created_at' => '2025-09-23 06:55:06',
    'updated_at' => '2026-01-21 04:13:35',
    'deleted_at' => '2026-01-21 04:13:35',
    'field_config' => NULL,
    'custom_fields_config' => NULL,
  ),
  2 => 
  array (
    'id' => 3,
    'name' => '인사말',
    'slug' => 'greetings',
    'description' => NULL,
    'skin_id' => 1,
    'template_id' => NULL,
    'is_active' => 1,
    'table_created' => 0,
    'list_count' => 10,
    'enable_notice' => 1,
    'is_single_page' => 1,
    'enable_sorting' => 0,
    'hot_threshold' => 100,
    'permission_read' => 'all',
    'permission_write' => 'all',
    'permission_comment' => 'all',
    'created_at' => '2025-09-23 07:34:15',
    'updated_at' => '2026-01-21 04:13:37',
    'deleted_at' => '2026-01-21 04:13:37',
    'field_config' => NULL,
    'custom_fields_config' => '"[{\\"name\\": \\"greeting_ko\\", \\"type\\": \\"editor\\", \\"label\\": \\"인사말 국문\\", \\"options\\": null, \\"required\\": false, \\"max_length\\": null, \\"placeholder\\": null}, {\\"name\\": \\"greeting_en\\", \\"type\\": \\"editor\\", \\"label\\": \\"인사말 영문\\", \\"options\\": null, \\"required\\": false, \\"max_length\\": null, \\"placeholder\\": null}, {\\"name\\": \\"ceo_message_ko\\", \\"type\\": \\"text\\", \\"label\\": \\"대표이사 국문\\", \\"options\\": null, \\"required\\": false, \\"max_length\\": null, \\"placeholder\\": null}, {\\"name\\": \\"ceo_message_en\\", \\"type\\": \\"text\\", \\"label\\": \\"대표이사 영문\\", \\"options\\": null, \\"required\\": false, \\"max_length\\": null, \\"placeholder\\": null}]"',
  ),
  3 => 
  array (
    'id' => 4,
    'name' => '자료실',
    'slug' => 'library',
    'description' => NULL,
    'skin_id' => 1,
    'template_id' => 5,
    'is_active' => 1,
    'table_created' => 0,
    'list_count' => 20,
    'enable_notice' => 1,
    'is_single_page' => 0,
    'enable_sorting' => 0,
    'hot_threshold' => 100,
    'permission_read' => 'all',
    'permission_write' => 'admin',
    'permission_comment' => 'member',
    'created_at' => '2026-01-21 04:13:22',
    'updated_at' => '2026-01-30 01:37:16',
    'deleted_at' => NULL,
    'field_config' => '{"title": {"label": "제목", "enabled": true, "required": true}, "content": {"label": "내용", "enabled": true, "required": true}, "category": {"label": "카테고리", "enabled": false, "required": false}, "password": {"label": "비밀번호", "enabled": false, "required": false}, "is_active": {"label": "노출여부", "enabled": false, "required": false}, "is_secret": {"label": "비밀글", "enabled": false, "required": false}, "thumbnail": {"label": "썸네일", "enabled": false, "required": false}, "created_at": {"label": "등록일", "enabled": true, "required": false}, "attachments": {"label": "첨부파일", "enabled": true, "required": false}, "author_name": {"label": "작성자", "enabled": true, "required": true}}',
    'custom_fields_config' => '[]',
  ),
  4 => 
  array (
    'id' => 5,
    'name' => 'FAQ',
    'slug' => 'faq',
    'description' => NULL,
    'skin_id' => 1,
    'template_id' => 3,
    'is_active' => 1,
    'table_created' => 0,
    'list_count' => 15,
    'enable_notice' => 0,
    'is_single_page' => 0,
    'enable_sorting' => 0,
    'hot_threshold' => 100,
    'permission_read' => 'all',
    'permission_write' => 'admin',
    'permission_comment' => 'member',
    'created_at' => '2026-01-21 04:14:41',
    'updated_at' => '2026-01-21 05:34:35',
    'deleted_at' => NULL,
    'field_config' => '{"title": {"label": "질문", "enabled": true, "required": true}, "content": {"label": "답변", "enabled": true, "required": true}, "category": {"label": "카테고리", "enabled": true, "required": false}, "password": {"label": "비밀번호", "enabled": false, "required": false}, "is_active": {"label": "노출여부", "enabled": false, "required": false}, "is_secret": {"label": "비밀글", "enabled": false, "required": false}, "thumbnail": {"label": "썸네일", "enabled": false, "required": false}, "created_at": {"label": "등록일", "enabled": true, "required": false}, "attachments": {"label": "첨부파일", "enabled": false, "required": false}, "author_name": {"label": "작성자", "enabled": true, "required": false}}',
    'custom_fields_config' => '[]',
  ),
  5 => 
  array (
    'id' => 6,
    'name' => '회원사 채용정보',
    'slug' => 'recruitments',
    'description' => NULL,
    'skin_id' => 1,
    'template_id' => 5,
    'is_active' => 1,
    'table_created' => 0,
    'list_count' => 20,
    'enable_notice' => 1,
    'is_single_page' => 0,
    'enable_sorting' => 0,
    'hot_threshold' => 100,
    'permission_read' => 'all',
    'permission_write' => 'admin',
    'permission_comment' => 'member',
    'created_at' => '2026-01-21 04:20:59',
    'updated_at' => '2026-01-30 01:37:16',
    'deleted_at' => NULL,
    'field_config' => '{"title": {"label": "제목", "enabled": true, "required": true}, "content": {"label": "내용", "enabled": true, "required": true}, "category": {"label": "카테고리", "enabled": false, "required": false}, "password": {"label": "비밀번호", "enabled": false, "required": false}, "is_active": {"label": "노출여부", "enabled": false, "required": false}, "is_secret": {"label": "비밀글", "enabled": false, "required": false}, "thumbnail": {"label": "썸네일", "enabled": false, "required": false}, "created_at": {"label": "등록일", "enabled": true, "required": false}, "attachments": {"label": "첨부파일", "enabled": true, "required": false}, "author_name": {"label": "작성자", "enabled": true, "required": true}}',
    'custom_fields_config' => '[]',
  ),
  6 => 
  array (
    'id' => 8,
    'name' => '정관 관리',
    'slug' => 'bylaws',
    'description' => NULL,
    'skin_id' => 1,
    'template_id' => 6,
    'is_active' => 1,
    'table_created' => 0,
    'list_count' => 15,
    'enable_notice' => 0,
    'is_single_page' => 1,
    'enable_sorting' => 0,
    'hot_threshold' => 100,
    'permission_read' => 'all',
    'permission_write' => 'member',
    'permission_comment' => 'member',
    'created_at' => '2026-01-22 01:57:52',
    'updated_at' => '2026-01-22 01:57:52',
    'deleted_at' => NULL,
    'field_config' => '{"title": {"label": "제목", "enabled": false, "required": false}, "content": {"label": "내용", "enabled": true, "required": true}, "category": {"label": "카테고리", "enabled": false, "required": false}, "password": {"label": "비밀번호", "enabled": false, "required": false}, "is_active": {"label": "노출여부", "enabled": false, "required": false}, "is_secret": {"label": "비밀글", "enabled": false, "required": false}, "thumbnail": {"label": "썸네일", "enabled": false, "required": false}, "created_at": {"label": "등록일", "enabled": false, "required": false}, "attachments": {"label": "첨부파일", "enabled": false, "required": false}, "author_name": {"label": "작성자", "enabled": false, "required": false}}',
    'custom_fields_config' => NULL,
  ),
  7 => 
  array (
    'id' => 9,
    'name' => '지난 행사',
    'slug' => 'past_events',
    'description' => NULL,
    'skin_id' => 1,
    'template_id' => 1,
    'is_active' => 1,
    'table_created' => 0,
    'list_count' => 20,
    'enable_notice' => 1,
    'is_single_page' => 0,
    'enable_sorting' => 0,
    'hot_threshold' => 100,
    'permission_read' => 'all',
    'permission_write' => 'admin',
    'permission_comment' => 'member',
    'created_at' => '2026-01-30 01:37:27',
    'updated_at' => '2026-01-30 01:37:27',
    'deleted_at' => NULL,
    'field_config' => '{"title": {"label": "제목", "enabled": true, "required": true}, "content": {"label": "내용", "enabled": true, "required": true}, "category": {"label": "카테고리", "enabled": false, "required": false}, "password": {"label": "비밀번호", "enabled": false, "required": false}, "is_active": {"label": "노출여부", "enabled": false, "required": false}, "is_secret": {"label": "비밀글", "enabled": false, "required": false}, "thumbnail": {"label": "썸네일", "enabled": false, "required": false}, "created_at": {"label": "등록일", "enabled": true, "required": false}, "attachments": {"label": "첨부파일", "enabled": true, "required": false}, "author_name": {"label": "작성자", "enabled": true, "required": true}}',
    'custom_fields_config' => NULL,
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