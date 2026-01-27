<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\Inquiry;
use App\Models\InquiryFile;
use App\Models\InquiryReply;
use App\Models\InquiryReplyFile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class InquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 기존 데이터 삭제
        InquiryReplyFile::query()->delete();
        InquiryFile::query()->delete();
        InquiryReply::query()->delete();
        Inquiry::query()->delete();

        // 회원 조회 또는 생성
        $members = Member::take(5)->get();
        
        if ($members->isEmpty()) {
            // 테스트용 회원 생성
            $members = collect();
            for ($i = 1; $i <= 5; $i++) {
                $member = Member::create([
                    'join_type' => 'email',
                    'email' => "test_member{$i}@example.com",
                    'login_id' => "test_member{$i}",
                    'password' => bcrypt('password123'),
                    'name' => "테스트회원{$i}",
                    'phone_number' => "010-1234-567{$i}",
                    'school_name' => "테스트학교{$i}",
                    'is_school_representative' => $i === 1,
                    'email_marketing_consent' => true,
                    'kakao_marketing_consent' => false,
                    'sms_marketing_consent' => false,
                    'terms_agreed_at' => now(),
                ]);
                $members->push($member);
            }
        }

        // 관리자 조회 (답변 작성용)
        $admin = User::where('role', 'admin')->orWhere('role', 'super_admin')->first();
        if (!$admin) {
            $admin = User::first();
        }

        $categories = ['교육', '자격증', '세미나', '해외연수', '기타'];
        $statuses = ['답변대기', '답변완료'];
        
        // 문의 데이터 생성
        $inquiries = [
            // 답변 완료된 문의들
            [
                'member_id' => $members[0]->id,
                'category' => '교육',
                'title' => '교육 프로그램 신청 방법에 대해 문의드립니다',
                'content' => '안녕하세요. 교육 프로그램에 참여하고 싶은데 신청 방법을 모르겠습니다. 
온라인으로 신청 가능한가요? 아니면 직접 방문해야 하나요?
신청 기간과 필요한 서류도 알려주시면 감사하겠습니다.',
                'status' => '답변완료',
                'views' => 15,
                'created_at' => Carbon::now()->subDays(10),
                'has_reply' => true,
                'has_files' => false,
            ],
            [
                'member_id' => $members[1]->id,
                'category' => '자격증',
                'title' => '자격증 시험 일정 문의',
                'content' => '자격증 시험 일정이 언제인지 알고 싶습니다.
올해 시험 일정과 접수 기간을 알려주세요.',
                'status' => '답변완료',
                'views' => 8,
                'created_at' => Carbon::now()->subDays(8),
                'has_reply' => true,
                'has_files' => true,
            ],
            [
                'member_id' => $members[2]->id,
                'category' => '세미나',
                'title' => '세미나 참가비 환불 문의',
                'content' => '세미나 참가 신청을 했는데 개인 사정으로 참석이 어려워졌습니다.
환불이 가능한지, 가능하다면 절차를 알려주세요.',
                'status' => '답변완료',
                'views' => 12,
                'created_at' => Carbon::now()->subDays(5),
                'has_reply' => true,
                'has_files' => false,
            ],
            // 답변 대기 중인 문의들
            [
                'member_id' => $members[0]->id,
                'category' => '해외연수',
                'title' => '해외연수 프로그램 상세 정보 요청',
                'content' => '해외연수 프로그램에 관심이 있습니다.
프로그램 기간, 비용, 신청 자격 등 상세 정보를 알려주시면 감사하겠습니다.',
                'status' => '답변대기',
                'views' => 5,
                'created_at' => Carbon::now()->subDays(3),
                'has_reply' => false,
                'has_files' => true,
            ],
            [
                'member_id' => $members[3]->id,
                'category' => '기타',
                'title' => '회원 정보 변경 문의',
                'content' => '학교명이 변경되어 회원 정보를 수정하고 싶습니다.
어떻게 수정할 수 있나요?',
                'status' => '답변대기',
                'views' => 3,
                'created_at' => Carbon::now()->subDays(2),
                'has_reply' => false,
                'has_files' => false,
            ],
            [
                'member_id' => $members[1]->id,
                'category' => '교육',
                'title' => '교육 자료 다운로드 권한 문의',
                'content' => '교육 자료를 다운로드하려고 하는데 권한이 없다고 나옵니다.
어떻게 해야 자료를 받을 수 있나요?',
                'status' => '답변대기',
                'views' => 7,
                'created_at' => Carbon::now()->subDay(),
                'has_reply' => false,
                'has_files' => false,
            ],
            [
                'member_id' => $members[4]->id,
                'category' => '자격증',
                'title' => '자격증 재발급 신청 방법',
                'content' => '자격증을 분실했습니다. 재발급 신청은 어떻게 하나요?
필요한 서류와 비용도 알려주세요.',
                'status' => '답변대기',
                'views' => 2,
                'created_at' => Carbon::now()->subHours(12),
                'has_reply' => false,
                'has_files' => false,
            ],
            [
                'member_id' => $members[2]->id,
                'category' => '세미나',
                'title' => '세미나 자료 요청',
                'content' => '지난 세미나에 참석하지 못했습니다.
세미나 자료를 받을 수 있을까요?',
                'status' => '답변대기',
                'views' => 4,
                'created_at' => Carbon::now()->subHours(6),
                'has_reply' => false,
                'has_files' => false,
            ],
            [
                'member_id' => $members[0]->id,
                'category' => '교육',
                'title' => '온라인 교육 수강 방법',
                'content' => '온라인 교육을 수강하고 싶은데 접속 방법을 모르겠습니다.
로그인은 어디서 하나요?',
                'status' => '답변완료',
                'views' => 20,
                'created_at' => Carbon::now()->subDays(15),
                'has_reply' => true,
                'has_files' => true,
            ],
            [
                'member_id' => $members[3]->id,
                'category' => '기타',
                'title' => '회원 탈퇴 절차 문의',
                'content' => '회원 탈퇴를 하고 싶습니다.
탈퇴 절차를 알려주세요.',
                'status' => '답변완료',
                'views' => 10,
                'created_at' => Carbon::now()->subDays(7),
                'has_reply' => true,
                'has_files' => false,
            ],
        ];

        foreach ($inquiries as $inquiryData) {
            $hasReply = $inquiryData['has_reply'];
            $hasFiles = $inquiryData['has_files'];
            unset($inquiryData['has_reply'], $inquiryData['has_files']);

            // 문의 생성
            $inquiry = Inquiry::create($inquiryData);

            // 문의 첨부파일 생성 (일부 문의에만)
            if ($hasFiles) {
                InquiryFile::create([
                    'inquiry_id' => $inquiry->id,
                    'file_path' => 'inquiries/sample_file_' . $inquiry->id . '.pdf',
                    'file_name' => '문의첨부파일_' . $inquiry->id . '.pdf',
                    'file_size' => 102400, // 100KB
                ]);
            }

            // 답변 생성 (답변 완료된 문의에만)
            if ($hasReply && $inquiry->status === '답변완료') {
                $reply = InquiryReply::create([
                    'inquiry_id' => $inquiry->id,
                    'admin_id' => $admin ? $admin->id : null,
                    'author' => $admin ? $admin->name : '관리자',
                    'content' => $this->getReplyContent($inquiry->category),
                    'status' => '답변완료',
                    'reply_date' => $inquiry->created_at->addDays(1),
                    'created_at' => $inquiry->created_at->addDays(1),
                    'updated_at' => $inquiry->created_at->addDays(1),
                ]);

                // 답변 첨부파일 생성 (일부 답변에만)
                if (in_array($inquiry->category, ['자격증', '교육'])) {
                    InquiryReplyFile::create([
                        'inquiry_reply_id' => $reply->id,
                        'file_path' => 'inquiry-replies/reply_file_' . $reply->id . '.pdf',
                        'file_name' => '답변첨부파일_' . $reply->id . '.pdf',
                        'file_size' => 204800, // 200KB
                    ]);
                }
            }
        }
    }

    /**
     * 답변 내용 생성
     */
    private function getReplyContent(string $category): string
    {
        $replies = [
            '교육' => '안녕하세요. 교육 프로그램 신청은 홈페이지에서 온라인으로 가능합니다.
신청 기간은 매월 1일부터 15일까지이며, 필요한 서류는 신분증 사본과 소속 증명서입니다.
자세한 내용은 홈페이지 공지사항을 참고해주세요.',
            '자격증' => '자격증 시험 일정은 연 2회 실시되며, 상반기는 3월, 하반기는 9월입니다.
접수 기간은 시험일 1개월 전부터 2주간입니다.
자세한 일정은 홈페이지에서 확인하실 수 있습니다.',
            '세미나' => '세미나 참가비 환불은 세미나 시작 3일 전까지 신청하시면 가능합니다.
환불 신청은 고객센터로 연락주시거나 홈페이지에서 신청하실 수 있습니다.
환불 절차는 약 5-7일 소요됩니다.',
            '해외연수' => '해외연수 프로그램에 대한 상세 정보는 홈페이지의 해외연수 섹션에서 확인하실 수 있습니다.
프로그램 기간은 2주에서 4주까지 다양하며, 비용은 프로그램에 따라 상이합니다.
신청 자격은 회원 가입 후 1년 이상 활동하신 회원분들입니다.',
            '기타' => '문의해주신 내용에 대해 검토 후 답변드리겠습니다.
추가로 필요한 정보가 있으시면 언제든지 문의해주세요.',
        ];

        return $replies[$category] ?? $replies['기타'];
    }
}
