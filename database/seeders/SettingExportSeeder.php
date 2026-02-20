<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: settings
 */
class SettingExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'settings';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'site_title' => '사단법인 전국대학연구·산학협력관리자협회',
    'site_url' => 'https://homepagekorea.net/',
    'admin_email' => 'cdg9572@gmail.com',
    'company_name' => NULL,
    'company_address' => 'Hwagok-dong',
    'company_tel' => NULL,
    'logo_path' => '/storage/settings/9kGYFymMEoyqKb5K3FHuuUk3s3Nnmf6as6Uikj2u.jpg',
    'favicon_path' => NULL,
    'footer_text' => NULL,
    'created_at' => '2026-01-20 15:00:16',
    'updated_at' => '2026-01-21 05:05:33',
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