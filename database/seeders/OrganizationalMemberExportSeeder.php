<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: organizational_members
 */
class OrganizationalMemberExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'organizational_members';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'category' => '회장',
    'name' => '박준철',
    'position' => '회장 / 이사',
    'affiliation' => '서울대학교',
    'phone' => '02-880-2040',
    'sort_order' => 99,
    'created_at' => '2026-01-21 15:02:56',
    'updated_at' => '2026-02-19 22:32:13',
  ),
  1 => 
  array (
    'id' => 3,
    'category' => '부회장',
    'name' => '성혁건',
    'position' => '부회장',
    'affiliation' => '원광대학교',
    'phone' => '063-850-5701',
    'sort_order' => 83,
    'created_at' => '2026-01-21 15:09:01',
    'updated_at' => '2026-02-19 22:27:42',
  ),
  2 => 
  array (
    'id' => 5,
    'category' => '부회장',
    'name' => '김광제',
    'position' => '부회장 / 이사',
    'affiliation' => '한밭대학교',
    'phone' => '042-821-1605',
    'sort_order' => 82,
    'created_at' => '2026-02-19 22:05:27',
    'updated_at' => '2026-02-19 22:27:46',
  ),
  3 => 
  array (
    'id' => 6,
    'category' => '부회장',
    'name' => '조용신',
    'position' => '부회장',
    'affiliation' => '동국대학교',
    'phone' => '02-2260-3654',
    'sort_order' => 81,
    'created_at' => '2026-02-19 22:06:58',
    'updated_at' => '2026-02-19 22:27:51',
  ),
  4 => 
  array (
    'id' => 7,
    'category' => '감사',
    'name' => '이흥우',
    'position' => '감사',
    'affiliation' => '충남대학교',
    'phone' => '042-821-7172',
    'sort_order' => 59,
    'created_at' => '2026-02-19 22:07:33',
    'updated_at' => '2026-02-19 22:32:04',
  ),
  5 => 
  array (
    'id' => 8,
    'category' => '사무국',
    'name' => '윤대성',
    'position' => '사무국장',
    'affiliation' => '사단법인 전국대학연구산학협력관리자협회',
    'phone' => '-',
    'sort_order' => 79,
    'created_at' => '2026-02-19 22:08:47',
    'updated_at' => '2026-02-19 22:28:05',
  ),
  6 => 
  array (
    'id' => 9,
    'category' => '사무국',
    'name' => '조은혜',
    'position' => '기획부장',
    'affiliation' => '부산대학교',
    'phone' => '051-510-3018',
    'sort_order' => 78,
    'created_at' => '2026-02-19 22:10:46',
    'updated_at' => '2026-02-19 22:28:26',
  ),
  7 => 
  array (
    'id' => 10,
    'category' => '사무국',
    'name' => '이기옥',
    'position' => '정책부장',
    'affiliation' => '고려대학교',
    'phone' => '02-3290-5834',
    'sort_order' => 77,
    'created_at' => '2026-02-19 22:11:48',
    'updated_at' => '2026-02-19 22:28:33',
  ),
  8 => 
  array (
    'id' => 11,
    'category' => '사무국',
    'name' => '장수웅',
    'position' => '대외협력부장',
    'affiliation' => '광운대학교',
    'phone' => '02-940-5635',
    'sort_order' => 76,
    'created_at' => '2026-02-19 22:12:28',
    'updated_at' => '2026-02-19 22:28:53',
  ),
  9 => 
  array (
    'id' => 12,
    'category' => '사무국',
    'name' => '김연아',
    'position' => '사업부장',
    'affiliation' => '전북대학교',
    'phone' => '063-270-2106',
    'sort_order' => 75,
    'created_at' => '2026-02-19 22:12:55',
    'updated_at' => '2026-02-19 22:29:05',
  ),
  10 => 
  array (
    'id' => 13,
    'category' => '사무국',
    'name' => '황보미',
    'position' => '교육부장',
    'affiliation' => '가톨릭대학교',
    'phone' => '02-3147-9361',
    'sort_order' => 74,
    'created_at' => '2026-02-19 22:13:23',
    'updated_at' => '2026-02-19 22:29:14',
  ),
  11 => 
  array (
    'id' => 14,
    'category' => '사무국',
    'name' => '배윤희',
    'position' => '홍보부장',
    'affiliation' => '경상국립대학교',
    'phone' => '055-772-0231',
    'sort_order' => 73,
    'created_at' => '2026-02-19 22:13:53',
    'updated_at' => '2026-02-19 22:29:22',
  ),
  12 => 
  array (
    'id' => 15,
    'category' => '사무국',
    'name' => '정진우',
    'position' => '운영부장',
    'affiliation' => '충북대학교',
    'phone' => '043-261-3865',
    'sort_order' => 72,
    'created_at' => '2026-02-19 22:14:17',
    'updated_at' => '2026-02-19 22:29:31',
  ),
  13 => 
  array (
    'id' => 16,
    'category' => '사무국',
    'name' => '손민석',
    'position' => '연구지원부장',
    'affiliation' => '성균관대학교',
    'phone' => '031-290-5105',
    'sort_order' => 71,
    'created_at' => '2026-02-19 22:14:47',
    'updated_at' => '2026-02-19 22:29:41',
  ),
  14 => 
  array (
    'id' => 17,
    'category' => '사무국',
    'name' => '김지혜',
    'position' => '팀장',
    'affiliation' => '사단법인 전국대학연구산학협력관리자협회',
    'phone' => NULL,
    'sort_order' => 70,
    'created_at' => '2026-02-19 22:15:08',
    'updated_at' => '2026-02-19 22:29:48',
  ),
  15 => 
  array (
    'id' => 18,
    'category' => '지회',
    'name' => '윤진한',
    'position' => '서울지회장',
    'affiliation' => '삼육대학교',
    'phone' => '02-3399-3901',
    'sort_order' => 66,
    'created_at' => '2026-02-19 22:15:38',
    'updated_at' => '2026-02-19 22:31:26',
  ),
  16 => 
  array (
    'id' => 19,
    'category' => '지회',
    'name' => '이광희',
    'position' => '경기인천강원지회장',
    'affiliation' => '상지대학교',
    'phone' => '033-738-7788',
    'sort_order' => 65,
    'created_at' => '2026-02-19 22:16:11',
    'updated_at' => '2026-02-19 22:31:18',
  ),
  17 => 
  array (
    'id' => 20,
    'category' => '지회',
    'name' => '이재주',
    'position' => '대전충청세종지회장',
    'affiliation' => '한밭대학교',
    'phone' => '042-828-8476',
    'sort_order' => 64,
    'created_at' => '2026-02-19 22:16:42',
    'updated_at' => '2026-02-19 22:31:11',
  ),
  18 => 
  array (
    'id' => 21,
    'category' => '지회',
    'name' => '이해광',
    'position' => '대구경북지회장',
    'affiliation' => '경북대학교',
    'phone' => '053-950-2392',
    'sort_order' => 63,
    'created_at' => '2026-02-19 22:17:21',
    'updated_at' => '2026-02-19 22:31:04',
  ),
  19 => 
  array (
    'id' => 22,
    'category' => '지회',
    'name' => '김민영',
    'position' => '부산울산경남지회장 / 이사',
    'affiliation' => '부산대학교',
    'phone' => '051-510-8501',
    'sort_order' => 62,
    'created_at' => '2026-02-19 22:17:53',
    'updated_at' => '2026-02-19 22:30:57',
  ),
  20 => 
  array (
    'id' => 23,
    'category' => '지회',
    'name' => '이상호',
    'position' => '전북지회장',
    'affiliation' => '원광대학교',
    'phone' => '063-850-5880',
    'sort_order' => 61,
    'created_at' => '2026-02-19 22:19:09',
    'updated_at' => '2026-02-19 22:30:49',
  ),
  21 => 
  array (
    'id' => 24,
    'category' => '지회',
    'name' => '김유석',
    'position' => '광주전남제주지회장',
    'affiliation' => '전남대학교',
    'phone' => '062-530-5090',
    'sort_order' => 60,
    'created_at' => '2026-02-19 22:19:48',
    'updated_at' => '2026-02-19 22:30:39',
  ),
  22 => 
  array (
    'id' => 25,
    'category' => '고문',
    'name' => '김영규',
    'position' => '수석고문(제1대 회장)',
    'affiliation' => '고려대학교',
    'phone' => NULL,
    'sort_order' => 49,
    'created_at' => '2026-02-19 22:20:56',
    'updated_at' => '2026-02-19 22:32:31',
  ),
  23 => 
  array (
    'id' => 26,
    'category' => '고문',
    'name' => '박재율',
    'position' => '고문(제2대 회장)',
    'affiliation' => '연세대학교',
    'phone' => NULL,
    'sort_order' => 48,
    'created_at' => '2026-02-19 22:21:50',
    'updated_at' => '2026-02-19 22:32:37',
  ),
  24 => 
  array (
    'id' => 27,
    'category' => '고문',
    'name' => '오외석',
    'position' => '고문(제3대 회장)',
    'affiliation' => '경남대학교',
    'phone' => NULL,
    'sort_order' => 47,
    'created_at' => '2026-02-19 22:23:23',
    'updated_at' => '2026-02-19 22:32:43',
  ),
  25 => 
  array (
    'id' => 28,
    'category' => '고문',
    'name' => '이종무',
    'position' => '고문(제4대 회장)',
    'affiliation' => '안양대학교',
    'phone' => NULL,
    'sort_order' => 46,
    'created_at' => '2026-02-19 22:23:43',
    'updated_at' => '2026-02-19 22:32:50',
  ),
  26 => 
  array (
    'id' => 29,
    'category' => '고문',
    'name' => '김종태',
    'position' => '고문(제5대 회장)',
    'affiliation' => '대구대학교',
    'phone' => NULL,
    'sort_order' => 45,
    'created_at' => '2026-02-19 22:24:04',
    'updated_at' => '2026-02-19 22:32:54',
  ),
  27 => 
  array (
    'id' => 30,
    'category' => '고문',
    'name' => '이명섭',
    'position' => '고문(제6대 회장)',
    'affiliation' => '인제대학교',
    'phone' => NULL,
    'sort_order' => 44,
    'created_at' => '2026-02-19 22:33:33',
    'updated_at' => '2026-02-19 22:36:09',
  ),
  28 => 
  array (
    'id' => 31,
    'category' => '고문',
    'name' => '최해대',
    'position' => '고문(제7대 회장)',
    'affiliation' => '동아대학교',
    'phone' => NULL,
    'sort_order' => 43,
    'created_at' => '2026-02-19 22:34:04',
    'updated_at' => '2026-02-19 22:36:16',
  ),
  29 => 
  array (
    'id' => 32,
    'category' => '고문',
    'name' => '김지룡',
    'position' => '고문(제8대 회장)',
    'affiliation' => '고려대학교',
    'phone' => NULL,
    'sort_order' => 42,
    'created_at' => '2026-02-19 22:34:28',
    'updated_at' => '2026-02-19 22:36:22',
  ),
  30 => 
  array (
    'id' => 33,
    'category' => '고문',
    'name' => '박지호',
    'position' => '고문(제9대 회장)',
    'affiliation' => '동아대학교',
    'phone' => NULL,
    'sort_order' => 41,
    'created_at' => '2026-02-19 22:34:49',
    'updated_at' => '2026-02-19 22:36:30',
  ),
  31 => 
  array (
    'id' => 34,
    'category' => '고문',
    'name' => '이동을',
    'position' => '고문(제10대 회장)',
    'affiliation' => '전주대학교',
    'phone' => NULL,
    'sort_order' => 40,
    'created_at' => '2026-02-19 22:35:08',
    'updated_at' => '2026-02-19 22:36:35',
  ),
  32 => 
  array (
    'id' => 35,
    'category' => '고문',
    'name' => '박병록',
    'position' => '고문(제11대 회장)',
    'affiliation' => '연세대학교',
    'phone' => NULL,
    'sort_order' => 39,
    'created_at' => '2026-02-19 22:35:30',
    'updated_at' => '2026-02-19 22:36:41',
  ),
  33 => 
  array (
    'id' => 36,
    'category' => '고문',
    'name' => '정관수',
    'position' => '고문(제12대 고문)',
    'affiliation' => '인제대학교',
    'phone' => NULL,
    'sort_order' => 38,
    'created_at' => '2026-02-19 22:35:58',
    'updated_at' => '2026-02-19 22:36:45',
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