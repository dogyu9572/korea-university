<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: admin_groups
 */
class AdminGroupExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'admin_groups';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'name' => '총괄계정',
    'description' => NULL,
    'is_active' => 1,
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