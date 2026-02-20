<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: popups
 */
class PopupExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'popups';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'title' => '신규 서비스 안내',
    'start_date' => NULL,
    'end_date' => NULL,
    'use_period' => 0,
    'width' => 500,
    'height' => 400,
    'position_top' => 100,
    'position_left' => 200,
    'url' => 'https://example.com/new-service',
    'url_target' => '_blank',
    'popup_type' => 'image',
    'popup_display_type' => 'normal',
    'popup_image' => NULL,
    'popup_content' => NULL,
    'is_active' => 1,
    'sort_order' => 100,
    'created_at' => '2026-01-20 15:00:16',
    'updated_at' => '2026-02-11 15:05:49',
    'deleted_at' => '2026-02-11 15:05:49',
  ),
  1 => 
  array (
    'id' => 2,
    'title' => '이벤트 공지',
    'start_date' => NULL,
    'end_date' => NULL,
    'use_period' => 0,
    'width' => 600,
    'height' => 500,
    'position_top' => 150,
    'position_left' => 150,
    'url' => 'https://example.com/event',
    'url_target' => '_self',
    'popup_type' => 'html',
    'popup_display_type' => 'normal',
    'popup_image' => NULL,
    'popup_content' => '<div style="text-align: center; padding: 20px;"><h3>특별 이벤트</h3><p>지금 참여하세요!</p><button onclick="window.close()">닫기</button></div>',
    'is_active' => 0,
    'sort_order' => 90,
    'created_at' => '2026-01-20 15:00:16',
    'updated_at' => '2026-02-11 15:05:46',
    'deleted_at' => '2026-02-11 15:05:46',
  ),
  2 => 
  array (
    'id' => 3,
    'title' => '시스템 점검 안내',
    'start_date' => NULL,
    'end_date' => NULL,
    'use_period' => 0,
    'width' => 400,
    'height' => 300,
    'position_top' => 200,
    'position_left' => 300,
    'url' => 'https://example.com/maintenance',
    'url_target' => '_blank',
    'popup_type' => 'image',
    'popup_display_type' => 'layer',
    'popup_image' => 'popups/eELwHWsMJdF8sJRWtplvTknfXA8YOoAjgPzBBKB1.jpg',
    'popup_content' => NULL,
    'is_active' => 1,
    'sort_order' => 80,
    'created_at' => '2026-01-20 15:00:16',
    'updated_at' => '2026-02-11 15:05:51',
    'deleted_at' => '2026-02-11 15:05:51',
  ),
  3 => 
  array (
    'id' => 4,
    'title' => '팝업 테스트',
    'start_date' => NULL,
    'end_date' => NULL,
    'use_period' => 0,
    'width' => 500,
    'height' => 750,
    'position_top' => 100,
    'position_left' => 100,
    'url' => NULL,
    'url_target' => '_blank',
    'popup_type' => 'image',
    'popup_display_type' => 'normal',
    'popup_image' => 'popups/MGrnTLNJp1KLJICh9xYAaj35owrZuJ6Wd2Lt3S1f.jpg',
    'popup_content' => NULL,
    'is_active' => 1,
    'sort_order' => 0,
    'created_at' => '2026-02-10 04:03:20',
    'updated_at' => '2026-02-11 15:05:53',
    'deleted_at' => '2026-02-11 15:05:53',
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