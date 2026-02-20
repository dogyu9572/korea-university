<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: inquiry_reply_files
 */
class InquiryReplyFileExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'inquiry_reply_files';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'inquiry_reply_id' => 1,
    'file_path' => 'inquiry-replies/reply_file_1.pdf',
    'file_name' => '답변첨부파일_1.pdf',
    'file_size' => 204800,
    'created_at' => '2026-01-23 06:13:27',
    'updated_at' => '2026-01-23 06:13:27',
  ),
  1 => 
  array (
    'id' => 2,
    'inquiry_reply_id' => 2,
    'file_path' => 'inquiry-replies/reply_file_2.pdf',
    'file_name' => '답변첨부파일_2.pdf',
    'file_size' => 204800,
    'created_at' => '2026-01-23 06:13:27',
    'updated_at' => '2026-01-23 06:13:27',
  ),
  2 => 
  array (
    'id' => 3,
    'inquiry_reply_id' => 4,
    'file_path' => 'inquiry-replies/reply_file_4.pdf',
    'file_name' => '답변첨부파일_4.pdf',
    'file_size' => 204800,
    'created_at' => '2026-01-23 06:13:27',
    'updated_at' => '2026-01-23 06:13:27',
  ),
  3 => 
  array (
    'id' => 4,
    'inquiry_reply_id' => 16,
    'file_path' => 'inquiry-replies/WJa4bNebQW4iQxDWWbK04sJ4PpqxsNc0NT95UAGa.jpg',
    'file_name' => '7a3e490962eb791f195cbfc48a277174.jpeg',
    'file_size' => 66041,
    'created_at' => '2026-01-23 08:44:14',
    'updated_at' => '2026-01-23 08:44:14',
  ),
  4 => 
  array (
    'id' => 5,
    'inquiry_reply_id' => 19,
    'file_path' => 'inquiry-replies/Gijz79fB0N2GQsXoyr4lxsgBy5MIHxzvpgJCUr3Y.png',
    'file_name' => 'z05.png',
    'file_size' => 3241,
    'created_at' => '2026-02-10 06:41:21',
    'updated_at' => '2026-02-10 06:41:21',
  ),
  5 => 
  array (
    'id' => 6,
    'inquiry_reply_id' => 19,
    'file_path' => 'inquiry-replies/EuJKmxSndyHLHaVOMjYsHPBRGr7BpLNf9o6u74Ui.png',
    'file_name' => 'zz01.png',
    'file_size' => 1638,
    'created_at' => '2026-02-10 06:41:21',
    'updated_at' => '2026-02-10 06:41:21',
  ),
  6 => 
  array (
    'id' => 7,
    'inquiry_reply_id' => 19,
    'file_path' => 'inquiry-replies/xV5gzj5Bzgp6xa8hguiWOcTQzceLNOqgnab94YC4.png',
    'file_name' => 'zzz02.png',
    'file_size' => 3229,
    'created_at' => '2026-02-10 06:41:21',
    'updated_at' => '2026-02-10 06:41:21',
  ),
  7 => 
  array (
    'id' => 8,
    'inquiry_reply_id' => 23,
    'file_path' => 'inquiry-replies/g7QsgG7OpONLeGqWp0zdg5BKl4fO9Xm341PklKgX.png',
    'file_name' => '20260219_090035.png',
    'file_size' => 248,
    'created_at' => '2026-02-19 09:10:11',
    'updated_at' => '2026-02-19 09:10:11',
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