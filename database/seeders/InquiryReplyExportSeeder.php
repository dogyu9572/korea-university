<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 서버 이관용 시더 (db:export-seeders 로 생성됨)
 * 테이블: inquiry_replies
 */
class InquiryReplyExportSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'inquiry_replies';
        $chunks = [
        array (
  0 => 
  array (
    'id' => 1,
    'inquiry_id' => 1,
    'admin_id' => 1,
    'author' => '홈페이지관리자',
    'content' => '안녕하세요. 교육 프로그램 신청은 홈페이지에서 온라인으로 가능합니다.
신청 기간은 매월 1일부터 15일까지이며, 필요한 서류는 신분증 사본과 소속 증명서입니다.
자세한 내용은 홈페이지 공지사항을 참고해주세요.',
    'status' => '답변완료',
    'reply_date' => '2026-01-14',
    'created_at' => '2026-01-14 06:13:27',
    'updated_at' => '2026-01-14 06:13:27',
  ),
  1 => 
  array (
    'id' => 2,
    'inquiry_id' => 2,
    'admin_id' => 1,
    'author' => '홈페이지관리자',
    'content' => '자격증 시험 일정은 연 2회 실시되며, 상반기는 3월, 하반기는 9월입니다.
접수 기간은 시험일 1개월 전부터 2주간입니다.
자세한 일정은 홈페이지에서 확인하실 수 있습니다.',
    'status' => '답변완료',
    'reply_date' => '2026-01-16',
    'created_at' => '2026-01-16 06:13:27',
    'updated_at' => '2026-01-16 06:13:27',
  ),
  2 => 
  array (
    'id' => 3,
    'inquiry_id' => 3,
    'admin_id' => 1,
    'author' => '홈페이지관리자',
    'content' => '세미나 참가비 환불은 세미나 시작 3일 전까지 신청하시면 가능합니다.
환불 신청은 고객센터로 연락주시거나 홈페이지에서 신청하실 수 있습니다.
환불 절차는 약 5-7일 소요됩니다.',
    'status' => '답변완료',
    'reply_date' => '2026-01-19',
    'created_at' => '2026-01-19 06:13:27',
    'updated_at' => '2026-01-19 06:13:27',
  ),
  3 => 
  array (
    'id' => 4,
    'inquiry_id' => 9,
    'admin_id' => 1,
    'author' => '홈페이지관리자',
    'content' => '안녕하세요. 교육 프로그램 신청은 홈페이지에서 온라인으로 가능합니다.
신청 기간은 매월 1일부터 15일까지이며, 필요한 서류는 신분증 사본과 소속 증명서입니다.
자세한 내용은 홈페이지 공지사항을 참고해주세요.',
    'status' => '답변완료',
    'reply_date' => '2026-01-09',
    'created_at' => '2026-01-09 06:13:27',
    'updated_at' => '2026-01-09 06:13:27',
  ),
  4 => 
  array (
    'id' => 5,
    'inquiry_id' => 10,
    'admin_id' => 1,
    'author' => '홈페이지관리자',
    'content' => '문의해주신 내용에 대해 검토 후 답변드리겠습니다.
추가로 필요한 정보가 있으시면 언제든지 문의해주세요.',
    'status' => '답변완료',
    'reply_date' => '2026-01-17',
    'created_at' => '2026-01-17 06:13:27',
    'updated_at' => '2026-01-17 06:13:27',
  ),
  5 => 
  array (
    'id' => 16,
    'inquiry_id' => 8,
    'admin_id' => 1,
    'author' => '홈페이지관리자',
    'content' => 'ㅁㄴㅇㄹ',
    'status' => '답변완료',
    'reply_date' => NULL,
    'created_at' => '2026-01-23 07:20:49',
    'updated_at' => '2026-01-23 08:34:21',
  ),
  6 => 
  array (
    'id' => 17,
    'inquiry_id' => 11,
    'admin_id' => 1,
    'author' => '홈페이지관리자',
    'content' => '<p>ㅁㄴㅇㄹ</p>',
    'status' => '답변완료',
    'reply_date' => '2026-02-13',
    'created_at' => '2026-02-05 06:35:44',
    'updated_at' => '2026-02-05 06:35:44',
  ),
  7 => 
  array (
    'id' => 18,
    'inquiry_id' => 12,
    'admin_id' => 1,
    'author' => '홈페이지관리자',
    'content' => '<span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span><span style="color: rgb(0, 0, 0); font-family: Arial; font-size: 13.3333px; letter-spacing: normal;">답변입니다.</span>',
    'status' => '답변완료',
    'reply_date' => '2026-02-10',
    'created_at' => '2026-02-10 03:55:53',
    'updated_at' => '2026-02-10 03:59:26',
  ),
  8 => 
  array (
    'id' => 19,
    'inquiry_id' => 15,
    'admin_id' => 1,
    'author' => '홈페이지관리자',
    'content' => '<p>ㅁㅎㅁㅎㅁ</p><p>ㅎㅁ</p><p>ㅎ</p><p>ㅁㅎ</p><p>ㅁㅎ</p><p>ㅁㅎㅁ</p><p>ㅎ</p><p><br></p><p>답변</p><p>이지</p><p><br></p><p><img src="https://korea-university.hk-test.co.kr/storage/uploads/editor/EHPGrmQCJD9xexPDkoTIDxII3ZjS9GgCaGi4NG2p.png" style="width: 799px;"><img src="https://korea-university.hk-test.co.kr/storage/uploads/editor/NcjD0ttNYNYwHUtpFmGIOrD6QhaIPi8od7IQVgfh.png" style="width: 799px;"><br></p>',
    'status' => '답변완료',
    'reply_date' => '2026-02-20',
    'created_at' => '2026-02-10 06:41:21',
    'updated_at' => '2026-02-10 06:41:21',
  ),
  9 => 
  array (
    'id' => 20,
    'inquiry_id' => 17,
    'admin_id' => 1,
    'author' => '홈페이지관리자',
    'content' => '<p>test입니다</p>',
    'status' => '답변완료',
    'reply_date' => '2026-02-12',
    'created_at' => '2026-02-11 13:01:57',
    'updated_at' => '2026-02-11 13:02:17',
  ),
  10 => 
  array (
    'id' => 21,
    'inquiry_id' => 19,
    'admin_id' => 1,
    'author' => '홈페이지관리자',
    'content' => '<p>ㅁㄴㅇㄹ</p>',
    'status' => '답변완료',
    'reply_date' => '2026-02-12',
    'created_at' => '2026-02-12 13:13:29',
    'updated_at' => '2026-02-12 13:13:29',
  ),
  11 => 
  array (
    'id' => 23,
    'inquiry_id' => 21,
    'admin_id' => 4,
    'author' => '관리자',
    'content' => '<p><font color="#000000" style="background-color: rgb(255, 255, 0);">테스트입니다.&nbsp;</font></p><p><font color="#000000" style="background-color: rgb(255, 255, 0);"><br></font></p><p><font color="#000000" style="background-color: rgb(255, 255, 0);">(ㅇㅇㅇㅇ</font></p><p><font color="#000000" style="background-color: rgb(255, 255, 0);"><br></font></p><p><font color="#000000" style="background-color: rgb(255, 255, 0);"><br></font></p><p><span style="letter-spacing: -0.28px; background-color: rgb(255, 255, 0);"><font color="#ff0000">테스트입니다.&nbsp;</font></span></p><p><span style="letter-spacing: -0.28px; background-color: rgb(255, 255, 0);"><font color="#ff0000"><br></font></span></p><p style="line-height: 3;"><span style="color: rgb(0, 0, 0); letter-spacing: -0.28px; background-color: rgb(255, 255, 0); font-size: 25px;">테스트입니다.&nbsp;</span><span style="letter-spacing: -0.28px; background-color: rgb(255, 255, 0);"><font color="#ff0000"></font></span><font color="#000000" style="background-color: rgb(255, 255, 0);"></font></p>',
    'status' => '답변완료',
    'reply_date' => '2026-02-18',
    'created_at' => '2026-02-19 09:10:11',
    'updated_at' => '2026-02-19 09:10:11',
  ),
  12 => 
  array (
    'id' => 24,
    'inquiry_id' => 22,
    'admin_id' => 4,
    'author' => '관리자',
    'content' => '<p>테스트입니다. 감사합니다.&nbsp;</p>',
    'status' => '답변완료',
    'reply_date' => '2026-02-19',
    'created_at' => '2026-02-19 18:01:13',
    'updated_at' => '2026-02-19 18:01:13',
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