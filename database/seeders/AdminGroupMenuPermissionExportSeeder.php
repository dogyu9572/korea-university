<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: admin_group_menu_permissions
 */
class AdminGroupMenuPermissionExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'admin_group_menu_permissions';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'group_id' => 1,
    'menu_id' => 1,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  1 => 
  array (
    'id' => 2,
    'group_id' => 1,
    'menu_id' => 2,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  2 => 
  array (
    'id' => 3,
    'group_id' => 1,
    'menu_id' => 4,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  3 => 
  array (
    'id' => 4,
    'group_id' => 1,
    'menu_id' => 7,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  4 => 
  array (
    'id' => 5,
    'group_id' => 1,
    'menu_id' => 9,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  5 => 
  array (
    'id' => 6,
    'group_id' => 1,
    'menu_id' => 3,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  6 => 
  array (
    'id' => 7,
    'group_id' => 1,
    'menu_id' => 6,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  7 => 
  array (
    'id' => 8,
    'group_id' => 1,
    'menu_id' => 20,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  8 => 
  array (
    'id' => 9,
    'group_id' => 1,
    'menu_id' => 23,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  9 => 
  array (
    'id' => 10,
    'group_id' => 1,
    'menu_id' => 39,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  10 => 
  array (
    'id' => 11,
    'group_id' => 1,
    'menu_id' => 47,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  11 => 
  array (
    'id' => 12,
    'group_id' => 1,
    'menu_id' => 48,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  12 => 
  array (
    'id' => 13,
    'group_id' => 1,
    'menu_id' => 68,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  13 => 
  array (
    'id' => 14,
    'group_id' => 1,
    'menu_id' => 49,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  14 => 
  array (
    'id' => 15,
    'group_id' => 1,
    'menu_id' => 51,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  15 => 
  array (
    'id' => 16,
    'group_id' => 1,
    'menu_id' => 56,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  16 => 
  array (
    'id' => 17,
    'group_id' => 1,
    'menu_id' => 57,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  17 => 
  array (
    'id' => 18,
    'group_id' => 1,
    'menu_id' => 58,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  18 => 
  array (
    'id' => 19,
    'group_id' => 1,
    'menu_id' => 59,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  19 => 
  array (
    'id' => 20,
    'group_id' => 1,
    'menu_id' => 60,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  20 => 
  array (
    'id' => 21,
    'group_id' => 1,
    'menu_id' => 61,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  21 => 
  array (
    'id' => 22,
    'group_id' => 1,
    'menu_id' => 62,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  22 => 
  array (
    'id' => 23,
    'group_id' => 1,
    'menu_id' => 63,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  23 => 
  array (
    'id' => 24,
    'group_id' => 1,
    'menu_id' => 64,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  24 => 
  array (
    'id' => 25,
    'group_id' => 1,
    'menu_id' => 65,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  25 => 
  array (
    'id' => 26,
    'group_id' => 1,
    'menu_id' => 66,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  26 => 
  array (
    'id' => 27,
    'group_id' => 1,
    'menu_id' => 67,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  27 => 
  array (
    'id' => 28,
    'group_id' => 1,
    'menu_id' => 19,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  28 => 
  array (
    'id' => 29,
    'group_id' => 1,
    'menu_id' => 10,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  29 => 
  array (
    'id' => 30,
    'group_id' => 1,
    'menu_id' => 17,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  30 => 
  array (
    'id' => 31,
    'group_id' => 1,
    'menu_id' => 40,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  31 => 
  array (
    'id' => 32,
    'group_id' => 1,
    'menu_id' => 71,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  32 => 
  array (
    'id' => 33,
    'group_id' => 1,
    'menu_id' => 50,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  33 => 
  array (
    'id' => 34,
    'group_id' => 1,
    'menu_id' => 21,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  34 => 
  array (
    'id' => 35,
    'group_id' => 1,
    'menu_id' => 18,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  35 => 
  array (
    'id' => 36,
    'group_id' => 1,
    'menu_id' => 8,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  36 => 
  array (
    'id' => 37,
    'group_id' => 1,
    'menu_id' => 45,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  37 => 
  array (
    'id' => 38,
    'group_id' => 1,
    'menu_id' => 46,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  38 => 
  array (
    'id' => 39,
    'group_id' => 1,
    'menu_id' => 52,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  39 => 
  array (
    'id' => 40,
    'group_id' => 1,
    'menu_id' => 41,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  40 => 
  array (
    'id' => 41,
    'group_id' => 1,
    'menu_id' => 42,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  41 => 
  array (
    'id' => 42,
    'group_id' => 1,
    'menu_id' => 43,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  42 => 
  array (
    'id' => 43,
    'group_id' => 1,
    'menu_id' => 44,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  43 => 
  array (
    'id' => 44,
    'group_id' => 1,
    'menu_id' => 69,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
  ),
  44 => 
  array (
    'id' => 45,
    'group_id' => 1,
    'menu_id' => 70,
    'granted' => 1,
    'created_at' => '2026-02-10 03:37:09',
    'updated_at' => '2026-02-10 03:37:09',
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