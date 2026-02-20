<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: organizational_charts
 */
class OrganizationalChartExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'organizational_charts';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'content' => '<div class="general">총회</div>
			<div class="side right">
				<div class="box">감사</div>
			</div>
			<div class="middle box dotc">이사회</div>
			<div class="head box dotc">회장</div>
			<div class="side left">
				<div class="box">고문</div>
			</div>
			<div class="btm">
				<div class="box">사무국</div>
			</div>',
    'created_at' => '2026-01-22 01:17:14',
    'updated_at' => '2026-02-12 11:06:20',
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