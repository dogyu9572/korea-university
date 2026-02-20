<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: banners
 */
class BannerExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'banners';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'title' => '신규 서비스 런칭',
    'main_text' => '새로운 기능을 만나보세요',
    'sub_text' => '더욱 편리해진 서비스',
    'url' => 'https://example.com/new-service',
    'url_target' => '_blank',
    'start_date' => '2026-01-13 15:00:16',
    'end_date' => '2026-02-19 15:00:16',
    'use_period' => 1,
    'is_active' => 1,
    'desktop_image' => NULL,
    'mobile_image' => NULL,
    'video_url' => NULL,
    'sort_order' => 3,
    'created_at' => '2026-01-20 15:00:16',
    'updated_at' => '2026-02-11 00:36:30',
    'deleted_at' => '2026-02-11 00:36:30',
  ),
  1 => 
  array (
    'id' => 2,
    'title' => '특별 이벤트',
    'main_text' => '지금 참여하세요',
    'sub_text' => '한정 기간 특가',
    'url' => 'https://example.com/event',
    'url_target' => '_self',
    'start_date' => NULL,
    'end_date' => NULL,
    'use_period' => 0,
    'is_active' => 1,
    'desktop_image' => NULL,
    'mobile_image' => NULL,
    'video_url' => NULL,
    'sort_order' => 2,
    'created_at' => '2026-01-20 15:00:16',
    'updated_at' => '2026-02-11 00:36:26',
    'deleted_at' => '2026-02-11 00:36:26',
  ),
  2 => 
  array (
    'id' => 3,
    'title' => '공지사항',
    'main_text' => '중요한 안내사항',
    'sub_text' => '확인해주세요',
    'url' => 'https://example.com/notice',
    'url_target' => '_self',
    'start_date' => '2026-01-19 15:00:16',
    'end_date' => '2026-01-27 15:00:16',
    'use_period' => 1,
    'is_active' => 1,
    'desktop_image' => NULL,
    'mobile_image' => NULL,
    'video_url' => NULL,
    'sort_order' => 1,
    'created_at' => '2026-01-20 15:00:16',
    'updated_at' => '2026-02-11 00:36:23',
    'deleted_at' => '2026-02-11 00:36:23',
  ),
  3 => 
  array (
    'id' => 4,
    'title' => 'TEST',
    'main_text' => NULL,
    'sub_text' => NULL,
    'url' => NULL,
    'url_target' => '_self',
    'start_date' => NULL,
    'end_date' => NULL,
    'use_period' => 0,
    'is_active' => 1,
    'desktop_image' => 'banners/MKYpkEMPJwqcwOxxIThEs6mPGcQOanXxZ584DleQ.jpg',
    'mobile_image' => NULL,
    'video_url' => NULL,
    'sort_order' => 4,
    'created_at' => '2026-01-21 08:10:00',
    'updated_at' => '2026-02-11 15:05:13',
    'deleted_at' => '2026-02-11 15:05:13',
  ),
  4 => 
  array (
    'id' => 5,
    'title' => '배너 테스트',
    'main_text' => NULL,
    'sub_text' => NULL,
    'url' => NULL,
    'url_target' => '_self',
    'start_date' => NULL,
    'end_date' => NULL,
    'use_period' => 0,
    'is_active' => 1,
    'desktop_image' => 'banners/9rCwSNqPuip9YVr3Gxufj6YMf9tjYR82zDRm7yJC.jpg',
    'mobile_image' => NULL,
    'video_url' => NULL,
    'sort_order' => 99,
    'created_at' => '2026-02-10 04:02:13',
    'updated_at' => '2026-02-11 15:05:11',
    'deleted_at' => '2026-02-11 15:05:11',
  ),
  5 => 
  array (
    'id' => 6,
    'title' => '메인배너',
    'main_text' => NULL,
    'sub_text' => NULL,
    'url' => 'https://korea-university.hk-test.co.kr/',
    'url_target' => '_self',
    'start_date' => NULL,
    'end_date' => NULL,
    'use_period' => 0,
    'is_active' => 1,
    'desktop_image' => 'banners/Qcn8nOkCzzoZJXdyrjjrmn742WubX5ZDwJVVZH6w.png',
    'mobile_image' => NULL,
    'video_url' => NULL,
    'sort_order' => 0,
    'created_at' => '2026-02-11 15:05:31',
    'updated_at' => '2026-02-11 15:05:31',
    'deleted_at' => NULL,
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