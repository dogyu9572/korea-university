<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: inquiry_files
 */
class InquiryFileExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'inquiry_files';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'inquiry_id' => 2,
    'file_path' => 'inquiries/sample_file_2.pdf',
    'file_name' => '문의첨부파일_2.pdf',
    'file_size' => 102400,
    'created_at' => '2026-01-23 06:13:27',
    'updated_at' => '2026-01-23 06:13:27',
  ),
  1 => 
  array (
    'id' => 2,
    'inquiry_id' => 4,
    'file_path' => 'inquiries/sample_file_4.pdf',
    'file_name' => '문의첨부파일_4.pdf',
    'file_size' => 102400,
    'created_at' => '2026-01-23 06:13:27',
    'updated_at' => '2026-01-23 06:13:27',
  ),
  2 => 
  array (
    'id' => 3,
    'inquiry_id' => 9,
    'file_path' => 'inquiries/sample_file_9.pdf',
    'file_name' => '문의첨부파일_9.pdf',
    'file_size' => 102400,
    'created_at' => '2026-01-23 06:13:27',
    'updated_at' => '2026-01-23 06:13:27',
  ),
  3 => 
  array (
    'id' => 4,
    'inquiry_id' => 11,
    'file_path' => 'inquiries/E77Dj0N0KJj8ZKsHJyBDfosOIdDvCCwu1DSkwFj3.jpg',
    'file_name' => '7a3e490962eb791f195cbfc48a277174.jpeg',
    'file_size' => 66041,
    'created_at' => '2026-02-05 06:33:13',
    'updated_at' => '2026-02-05 06:33:13',
  ),
  4 => 
  array (
    'id' => 5,
    'inquiry_id' => 11,
    'file_path' => 'inquiries/ykTsBebiJHG8PQwKzXi0bBu6pd9aSuaJyFFtqkO2.jpg',
    'file_name' => 'af8bd015be19edad8f295d671e41e01d_res.jpeg',
    'file_size' => 426910,
    'created_at' => '2026-02-05 06:33:13',
    'updated_at' => '2026-02-05 06:33:13',
  ),
  5 => 
  array (
    'id' => 6,
    'inquiry_id' => 12,
    'file_path' => 'inquiries/qzzyZT3RF3aQNRXB7i0jZErNowbk3oZtbv1HDGMN.jpg',
    'file_name' => 'lXDz7SeFD4kY4rWjZ6PIyOiU0EuZ5VvU7nAx5ZX3.jpg',
    'file_size' => 1504738,
    'created_at' => '2026-02-10 03:53:25',
    'updated_at' => '2026-02-10 03:53:25',
  ),
  6 => 
  array (
    'id' => 7,
    'inquiry_id' => 12,
    'file_path' => 'inquiries/iw1avVPzx8jV0OjXoG0t86rRm7itzrjVCveNmigA.jpg',
    'file_name' => 'maeva-vigier-OSqQ3ugcYQU-unsplash.jpg',
    'file_size' => 1504738,
    'created_at' => '2026-02-10 03:53:25',
    'updated_at' => '2026-02-10 03:53:25',
  ),
  7 => 
  array (
    'id' => 9,
    'inquiry_id' => 15,
    'file_path' => 'inquiries/gW3CwyOFsPXHWNAB4c3CZqqDNvuwvRQi6lfBDiU6.png',
    'file_name' => 'z03.png',
    'file_size' => 3372,
    'created_at' => '2026-02-10 06:40:39',
    'updated_at' => '2026-02-10 06:40:39',
  ),
  8 => 
  array (
    'id' => 10,
    'inquiry_id' => 15,
    'file_path' => 'inquiries/YLlQGW6TOqQlEosArz6WJumyWV1HNg7Dn7vhK82h.png',
    'file_name' => 'z04.png',
    'file_size' => 3026,
    'created_at' => '2026-02-10 06:40:39',
    'updated_at' => '2026-02-10 06:40:39',
  ),
  9 => 
  array (
    'id' => 11,
    'inquiry_id' => 15,
    'file_path' => 'inquiries/yxlUPOkfKcbDo9Z4Be8wORtkwI5u65rtX5puOqU9.png',
    'file_name' => 'z05.png',
    'file_size' => 3241,
    'created_at' => '2026-02-10 06:40:39',
    'updated_at' => '2026-02-10 06:40:39',
  ),
  10 => 
  array (
    'id' => 12,
    'inquiry_id' => 16,
    'file_path' => 'inquiries/E2QqubRObX8UgxLcuYRSOHWOycrN3Gw6AdY8aNYC.jpg',
    'file_name' => 'CleanShot 2026-02-11 at 11.10.26.jpg',
    'file_size' => 35104,
    'created_at' => '2026-02-11 02:11:17',
    'updated_at' => '2026-02-11 02:11:17',
  ),
  11 => 
  array (
    'id' => 13,
    'inquiry_id' => 16,
    'file_path' => 'inquiries/PiXAhe3ODjqP7BEG7FmcmrqY2qL3c4G0cbVvfcqs.jpg',
    'file_name' => 'CleanShot 2026-02-11 at 11.07.09.jpg',
    'file_size' => 177542,
    'created_at' => '2026-02-11 02:11:17',
    'updated_at' => '2026-02-11 02:11:17',
  ),
  12 => 
  array (
    'id' => 14,
    'inquiry_id' => 17,
    'file_path' => 'inquiries/29pqKHJq06HslnJOZZXHrSsWB7DdYBTWmETYKjF5.jpg',
    'file_name' => 'CleanShot 2026-02-11 at 11.10.26.jpg',
    'file_size' => 35104,
    'created_at' => '2026-02-11 02:11:47',
    'updated_at' => '2026-02-11 02:11:47',
  ),
  13 => 
  array (
    'id' => 15,
    'inquiry_id' => 17,
    'file_path' => 'inquiries/XRHiPbLgtK0OYPoGEo33JLSeAlLxgUf6XsIiM3Jh.jpg',
    'file_name' => 'CleanShot 2026-02-11 at 11.08.04.jpg',
    'file_size' => 57970,
    'created_at' => '2026-02-11 02:11:47',
    'updated_at' => '2026-02-11 02:11:47',
  ),
  14 => 
  array (
    'id' => 16,
    'inquiry_id' => 17,
    'file_path' => 'inquiries/W4hMi42pAhTGhwW8TQUa73zsxo2xGp8RCWqmqcos.jpg',
    'file_name' => 'CleanShot 2026-02-11 at 11.07.09.jpg',
    'file_size' => 177542,
    'created_at' => '2026-02-11 02:11:47',
    'updated_at' => '2026-02-11 02:11:47',
  ),
  15 => 
  array (
    'id' => 17,
    'inquiry_id' => 22,
    'file_path' => 'inquiries/SWNCkWzEyNux3LgZJpUTMrdtXj3pIFHHYtit6jac.png',
    'file_name' => 'sample_images_00.png',
    'file_size' => 6877,
    'created_at' => '2026-02-19 12:39:15',
    'updated_at' => '2026-02-19 12:39:15',
  ),
  16 => 
  array (
    'id' => 18,
    'inquiry_id' => 22,
    'file_path' => 'inquiries/jFsE4X9ew6X2MKDD10qBSxR6fZ3UlkiXoGSTC6M2.png',
    'file_name' => 'sample_images_01.png',
    'file_size' => 2953,
    'created_at' => '2026-02-19 12:39:15',
    'updated_at' => '2026-02-19 12:39:15',
  ),
  17 => 
  array (
    'id' => 19,
    'inquiry_id' => 22,
    'file_path' => 'inquiries/FLVgJuEllUfeWSURflZJCV5xCVhtlEQsiflCbHjr.png',
    'file_name' => 'sample_images_02.png',
    'file_size' => 5896,
    'created_at' => '2026-02-19 12:39:15',
    'updated_at' => '2026-02-19 12:39:15',
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