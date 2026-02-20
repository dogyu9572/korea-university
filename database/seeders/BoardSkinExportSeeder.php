<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: board_skins
 */
class BoardSkinExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'board_skins';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'name' => '기본 스킨',
    'directory' => 'default',
    'description' => '기본 게시판 스킨입니다. 모든 게시판에서 사용할 수 있는 기본 스킨입니다.',
    'thumbnail' => NULL,
    'options' => '"{\\"show_view_count\\":true,\\"show_date\\":true,\\"list_date_format\\":\\"Y-m-d\\",\\"view_date_format\\":\\"Y-m-d H:i\\"}"',
    'is_active' => 1,
    'is_default' => 1,
    'created_at' => '2025-05-05 09:33:08',
    'updated_at' => '2025-05-05 09:33:08',
  ),
  1 => 
  array (
    'id' => 2,
    'name' => '갤러리 스킨',
    'directory' => 'gallery',
    'description' => '갤러리 형태의 게시판에서 사용할 수 있는 이미지 중심 스킨입니다.',
    'thumbnail' => NULL,
    'options' => '"{\\"show_view_count\\":true,\\"show_date\\":true,\\"list_date_format\\":\\"Y-m-d\\",\\"view_date_format\\":\\"Y-m-d H:i\\",\\"gallery_columns\\":3,\\"thumbnail_width\\":300,\\"thumbnail_height\\":200}"',
    'is_active' => 1,
    'is_default' => 0,
    'created_at' => '2025-05-05 09:33:08',
    'updated_at' => '2025-05-05 09:33:08',
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