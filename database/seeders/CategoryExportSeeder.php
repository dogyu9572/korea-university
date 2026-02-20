<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: categories
 */
class CategoryExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'categories';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'parent_id' => NULL,
    'code' => 'C001',
    'name' => 'FAQ',
    'depth' => 0,
    'display_order' => 1,
    'is_active' => 1,
    'created_at' => '2026-01-21 05:34:03',
    'updated_at' => '2026-01-21 05:34:03',
  ),
  1 => 
  array (
    'id' => 2,
    'parent_id' => 1,
    'code' => 'C002',
    'name' => '교육',
    'depth' => 1,
    'display_order' => 1,
    'is_active' => 1,
    'created_at' => '2026-01-21 05:34:03',
    'updated_at' => '2026-01-21 05:34:03',
  ),
  2 => 
  array (
    'id' => 3,
    'parent_id' => 1,
    'code' => 'C003',
    'name' => '자격증',
    'depth' => 1,
    'display_order' => 2,
    'is_active' => 1,
    'created_at' => '2026-01-21 05:34:03',
    'updated_at' => '2026-01-21 05:34:03',
  ),
  3 => 
  array (
    'id' => 4,
    'parent_id' => 1,
    'code' => 'C004',
    'name' => '세미나',
    'depth' => 1,
    'display_order' => 3,
    'is_active' => 1,
    'created_at' => '2026-01-21 05:34:03',
    'updated_at' => '2026-01-21 05:34:03',
  ),
  4 => 
  array (
    'id' => 5,
    'parent_id' => 1,
    'code' => 'C005',
    'name' => '해외연수',
    'depth' => 1,
    'display_order' => 4,
    'is_active' => 1,
    'created_at' => '2026-01-21 05:34:03',
    'updated_at' => '2026-01-21 05:34:03',
  ),
  5 => 
  array (
    'id' => 6,
    'parent_id' => 1,
    'code' => 'C006',
    'name' => '기타',
    'depth' => 1,
    'display_order' => 5,
    'is_active' => 1,
    'created_at' => '2026-01-21 05:34:03',
    'updated_at' => '2026-01-21 05:34:03',
  ),
  6 => 
  array (
    'id' => 7,
    'parent_id' => NULL,
    'code' => 'V001',
    'name' => '시험장',
    'depth' => 0,
    'display_order' => 0,
    'is_active' => 1,
    'created_at' => '2026-01-27 01:26:29',
    'updated_at' => '2026-01-27 01:26:29',
  ),
  7 => 
  array (
    'id' => 8,
    'parent_id' => 7,
    'code' => 'V002',
    'name' => '서울대학교 시험센터',
    'depth' => 1,
    'display_order' => 0,
    'is_active' => 1,
    'created_at' => '2026-01-27 01:26:57',
    'updated_at' => '2026-01-27 01:26:57',
  ),
  8 => 
  array (
    'id' => 9,
    'parent_id' => 7,
    'code' => 'V002',
    'name' => '연세대학교 시험센터',
    'depth' => 1,
    'display_order' => 0,
    'is_active' => 1,
    'created_at' => '2026-01-27 01:27:10',
    'updated_at' => '2026-01-27 01:27:10',
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