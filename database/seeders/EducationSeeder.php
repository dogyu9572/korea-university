<?php

namespace Database\Seeders;

use App\Models\Education;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        Education::query()->delete();

        $educations = [
            [
                'education_type' => '정기교육',
                'education_class' => '6차',
                'is_public' => true,
                'application_status' => '접수중',
                'name' => '2025년 산학협력단 직원 전문성 강화 교육(기본과정)',
                'period_start' => Carbon::parse('2025-12-03'),
                'period_end' => Carbon::parse('2025-12-05'),
                'period_time' => '총 15시간',
                'is_accommodation' => true,
                'location' => '서울대학교 연구공원 943동 1층 세미나실',
                'target' => '산학협력단 및 사업단·센터 실무 연구관리자(입사 5년 이내)',
                'completion_criteria' => '교육이수율 90% 이상 + 설문조사 제출 시 수료증 발급',
                'certificate_type' => '수료증',
                'completion_hours' => 15,
                'application_start' => Carbon::parse('2025-11-04 09:00:00'),
                'application_end' => Carbon::parse('2025-11-28 18:00:00'),
                'capacity' => 40,
                'capacity_unlimited' => false,
                'payment_methods' => ['무통장입금', '온라인카드결제'],
                'deposit_account' => "농협은행 301-0334-8275-91 (전국대학연구산학협력관리자협의회)\n입금자명: 소속기관명 + 성명",
                'deposit_deadline_days' => 3,
                'fee_member_twin' => 570000,
                'fee_member_single' => 680000,
                'fee_member_no_stay' => 360000,
                'fee_guest_twin' => 670000,
                'fee_guest_single' => 780000,
                'fee_guest_no_stay' => 460000,
                'refund_twin_fee' => 130000,
                'refund_single_fee' => 260000,
                'refund_no_stay_fee' => 75000,
                'refund_twin_deadline' => '교육 시작일 기준 30일 전까지',
                'refund_single_deadline' => '교육 시작일 기준 30일 전까지',
                'refund_no_stay_deadline' => '교육 시작일 기준 1주일 전까지',
                'refund_same_day_fee' => 300000,
                'education_overview' => '<h4>교육명</h4><p>2025년 산학협력단 직원 전문성 강화 교육(기본과정)</p><h4>목적</h4><p>산학협력단 소속 직원의 역량 강화를 위해, 체계적이고 현장 중심적인 연구관리 및 산학협력 직무교육을 실시</p>',
                'education_schedule' => '<table><tr><th>차수</th><th>일정</th><th>장소</th><th>인원</th><th>비고</th></tr><tr><td>6차</td><td>2025.12.03.(수) ~ 12.05.(금)</td><td>서울대학교 연구공원 943동 1층 세미나실</td><td>40명</td><td>숙박교육(희망자)</td></tr></table>',
                'fee_info' => '<h4>교육 참가비 및 납부 안내</h4><table><tr><th>구분</th><th>2인 1실</th><th>1인실</th><th>비숙박</th></tr><tr><td>회원교(1인당)</td><td>570,000원</td><td>680,000원</td><td>360,000원</td></tr><tr><td>비회원교(1인당)</td><td>670,000원</td><td>780,000원</td><td>460,000원</td></tr></table>',
                'refund_policy' => '<h4>교육 취소 및 환불 규정</h4><table><tr><th>구분</th><th>수수료</th><th>무료 취소 기한</th></tr><tr><td>2인 1실</td><td>130,000원</td><td>교육 시작일 기준 30일 전까지</td></tr><tr><td>1인실</td><td>260,000원</td><td>교육 시작일 기준 30일 전까지</td></tr><tr><td>비숙박</td><td>참가비의 25%(75,000원)</td><td>교육 시작일 기준 1주일 전까지</td></tr></table>',
                'curriculum' => '<ul><li>1일차: 산학협력단 개요와 창업지원, 연구협약 절차 등 기본 이론 및 실무 이해</li><li>2일차: 연구비 감사·집행관리 실무와 사례분석, 대학별 사례 공유 및 토의</li><li>3일차: 연구비 정산 및 사용실적 보고 절차 학습 후 수료식 진행</li></ul>',
                'education_notice' => '<ul><li>차수별 참여인원: 회차별 40명 선착순 모집이며, 신청 인원이 미달될 경우 폐강될 수 있습니다.</li><li>수료 기준: 수료 교육 이수율 90% 이상 및 설문조사 제출 시 수료증 발급</li></ul>',
            ],
        ];

        foreach ($educations as $education) {
            Education::create($education);
        }
    }
}
