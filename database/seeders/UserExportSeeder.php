<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: users
 */
class UserExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'users';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'login_id' => 'homepage',
    'role' => 'super_admin',
    'admin_group_id' => NULL,
    'is_active' => 1,
    'last_login_at' => '2026-02-20 08:56:05',
    'name' => '홈페이지관리자',
    'email' => 'admin@homepage.com',
    'email_verified_at' => NULL,
    'password' => '$2y$12$4Ij09S8UbYdGT.bITkaDYeQSv5lK8Rz0YzMEpXLMT/YiaayiCWpZO',
    'department' => NULL,
    'position' => NULL,
    'contact' => NULL,
    'remember_token' => NULL,
    'created_at' => '2026-01-20 15:00:15',
    'updated_at' => '2026-02-20 08:56:05',
  ),
  1 => 
  array (
    'id' => 4,
    'login_id' => 'admin',
    'role' => 'admin',
    'admin_group_id' => 1,
    'is_active' => 1,
    'last_login_at' => '2026-02-20 09:00:35',
    'name' => '관리자',
    'email' => 'test@naver.com',
    'email_verified_at' => NULL,
    'password' => '$2y$12$.z7ShTY.ITaOUa3cptx/kOp9.ORL3S.tf4UAPYZxi/de8YlqGGEn6',
    'department' => NULL,
    'position' => NULL,
    'contact' => '010-0000-0000',
    'remember_token' => NULL,
    'created_at' => '2026-02-12 13:47:21',
    'updated_at' => '2026-02-20 09:00:35',
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