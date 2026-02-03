<?php

namespace Database\Seeders;

use App\Models\SeminarTraining;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SeminarTrainingSeeder extends Seeder
{
    public function run(): void
    {
        SeminarTraining::query()->delete();

        $programs = [
            [
                'type' => '세미나',
                'education_class' => '추계세미나',
                'is_public' => true,
                'application_status' => '접수중',
                'name' => '2025년 전국대학연구 산학협력관리자 협회 추계세미나',
                'period_start' => Carbon::parse('2025-11-05'),
                'period_end' => Carbon::parse('2025-11-07'),
                'period_time' => '3일',
                'is_accommodation' => true,
                'location' => '그랜드 하얏트 제주',
                'target' => '산학협력단 및 사업단, 센터 실무 연구관리자(입사 5년 이내 자)',
                'completion_criteria' => '전체 세션 90% 이상 참석',
                'certificate_type' => '수료증',
                'completion_hours' => 15,
                'application_start' => Carbon::parse('2025-09-24 09:00:00'),
                'application_end' => Carbon::parse('2025-10-14 17:00:00'),
                'capacity' => 400,
                'capacity_unlimited' => false,
                'capacity_per_school' => 5,
                'capacity_per_school_unlimited' => false,
                'payment_methods' => ['무통장입금'],
                'deposit_account' => '농협은행 301-0334-8275-91',
                'deposit_deadline_days' => 7,
                'fee_member_twin' => 750000,
                'fee_member_single' => 990000,
                'fee_member_no_stay' => 450000,
                'fee_guest_twin' => 850000,
                'fee_guest_single' => 1090000,
                'fee_guest_no_stay' => 550000,
                'refund_twin_fee' => 150000,
                'refund_single_fee' => 200000,
                'refund_no_stay_fee' => 100000,
                'refund_twin_deadline' => '30일 전',
                'refund_single_deadline' => '30일 전',
                'refund_no_stay_deadline' => '7일 전',
                'refund_same_day_fee' => 500000,
                'annual_fee' => 50000,
                'education_overview' => '<h4>세미나명</h4><p>2025년 전국대학연구 산학협력관리자 협회 추계세미나</p><h4>목적</h4><p>산학협력 정책 및 실무 사례 공유</p>',
                'education_schedule' => '<p>일정: 2025.11.05.(수) ~ 11.07.(금)</p><p>장소: 그랜드 하얏트 제주</p>',
                'fee_info' => '<table><tr><th>구분</th><th>2인 1실</th><th>1인실</th><th>비숙박</th></tr><tr><td>회원교</td><td>750,000원</td><td>990,000원</td><td>450,000원</td></tr></table>',
                'refund_policy' => '<p>취소 기한: 30일 전까지 무료 취소 가능</p>',
                'curriculum' => '<ul><li>산학협력 주요 정책 방향</li><li>연구비 관리 실무</li><li>대학별 우수 사례 발표</li></ul>',
                'education_notice' => '<p>정원: 400명 (선착순)</p><p>학교당 최대 5명</p>',
            ],
            [
                'type' => '해외연수',
                'education_class' => '해외연수',
                'is_public' => true,
                'application_status' => '접수마감',
                'name' => '2025년 연구·산학협력 벤치마킹 해외연수 안내',
                'period_start' => Carbon::parse('2025-08-26'),
                'period_end' => Carbon::parse('2025-09-03'),
                'period_time' => '9일',
                'is_accommodation' => true,
                'location' => '스페인, 이탈리아',
                'target' => '본 협회 회원교 소속 연구․산학협력 업무 담당자 및 관리자',
                'completion_criteria' => '전일정 참여',
                'certificate_type' => '수료증',
                'completion_hours' => 40,
                'application_start' => Carbon::parse('2025-06-24 10:00:00'),
                'application_end' => Carbon::parse('2025-06-26 17:00:00'),
                'capacity' => 60,
                'capacity_unlimited' => false,
                'payment_methods' => ['무통장입금'],
                'deposit_account' => '농협은행 301-0334-8275-91',
                'deposit_deadline_days' => 7,
                'fee_member_twin' => 6300000,
                'fee_member_single' => 7860000,
                'refund_twin_deadline' => '30일 전',
                'refund_single_deadline' => '30일 전',
                'education_overview' => '<h4>연수명</h4><p>2025년 연구·산학협력 벤치마킹 해외연수</p><h4>목적</h4><p>해외 우수 연구기관 벤치마킹</p>',
                'education_schedule' => '<p>연수기간: 2025.08.26.(화) ~ 09.03.(수)</p><p>연수국가: 스페인, 이탈리아</p>',
                'fee_info' => '<table><tr><th>구분</th><th>2인 1실</th><th>1인실</th></tr><tr><td>참가비</td><td>6,300,000원</td><td>7,860,000원</td></tr></table>',
                'refund_policy' => '<p>취소 기한: 30일 전까지 가능</p>',
                'curriculum' => '<ul><li>바르셀로나 바이오메디컬연구단지 방문</li><li>카탈루냐 기술센터 견학</li><li>로마 토르 베리가타 대학 방문</li></ul>',
                'education_notice' => '<p>정원: 60명 (선착순)</p>',
            ],
        ];

        foreach ($programs as $program) {
            SeminarTraining::create($program);
        }
    }
}
