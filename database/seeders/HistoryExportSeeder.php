<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: histories
 */
class HistoryExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'histories';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'title' => '제2차 세미나 개최',
    'date' => '1995-04-20',
    'date_end' => '1995-04-20',
    'year' => 1995,
    'content' => '장소 : 한국과학재단 대강당 / 참가대학 : 96개 대학 / 주제 : 대학 연구비중앙관리제도 조기 정착을 위한 발전방안 등 5개 과제',
    'is_visible' => 'Y',
    'created_at' => '2026-01-22 01:44:23',
    'updated_at' => '2026-02-19 18:50:14',
  ),
  1 => 
  array (
    'id' => 3,
    'title' => '제 1 대 회장 고려대학교 김 영 규',
    'date' => '1994-04-15',
    'date_end' => NULL,
    'year' => 1994,
    'content' => '제 1 대 회장 고려대학교 김 영 규',
    'is_visible' => 'Y',
    'created_at' => '2026-01-22 01:51:33',
    'updated_at' => '2026-02-19 18:45:16',
  ),
  2 => 
  array (
    'id' => 4,
    'title' => '전국대학연구관리자협의회 결성',
    'date' => '1994-04-15',
    'date_end' => NULL,
    'year' => 1994,
    'content' => '장소 : 한국학술진흥재단 강당 / 참가대학 : 98개 대학',
    'is_visible' => 'Y',
    'created_at' => '2026-01-22 01:51:33',
    'updated_at' => '2026-02-19 18:43:13',
  ),
  3 => 
  array (
    'id' => 6,
    'title' => '제1차 세미나 개최',
    'date' => '1994-10-20',
    'date_end' => '1994-10-22',
    'year' => 1994,
    'content' => '장소 : 강원도 오색 그린야드 호텔 / 참가대학 : 115개 대학',
    'is_visible' => 'Y',
    'created_at' => '2026-02-02 05:17:55',
    'updated_at' => '2026-02-19 18:48:34',
  ),
  4 => 
  array (
    'id' => 7,
    'title' => '제3차 세미나 개최',
    'date' => '1995-10-18',
    'date_end' => '1995-10-20',
    'year' => 1995,
    'content' => '장소 : 경주교육문화회관 / 참가대학 : 102개 대학 / 주제 : 부설연구소 평가사례 등 4개 과제',
    'is_visible' => 'Y',
    'created_at' => '2026-02-02 05:20:06',
    'updated_at' => '2026-02-19 18:51:47',
  ),
  5 => 
  array (
    'id' => 8,
    'title' => '제 2대 회장 연세대학교 박재율',
    'date' => '1995-10-20',
    'date_end' => NULL,
    'year' => 1995,
    'content' => '제 2대 회장 연세대학교 박재율',
    'is_visible' => 'Y',
    'created_at' => '2026-02-19 18:53:02',
    'updated_at' => '2026-02-19 18:53:02',
  ),
  6 => 
  array (
    'id' => 9,
    'title' => '제4차 세미나 개최',
    'date' => '1996-04-26',
    'date_end' => NULL,
    'year' => 1996,
    'content' => '장소 : 한국학술진흥재단 대강당 / 참가대학 : 96개 대학 / 주제 : 대학종합평가와 연구개방의 과제 등 3개 과제',
    'is_visible' => 'Y',
    'created_at' => '2026-02-19 18:54:30',
    'updated_at' => '2026-02-19 18:54:30',
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