<?php

namespace Database\Seeders;

use App\Models\OnlineEducation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OnlineEducationSeeder extends Seeder
{
    public function run(): void
    {
        OnlineEducation::query()->delete();

        $onlineEducations = [
            [
                'education_class' => '1기',
                'is_public' => true,
                'application_status' => '접수중',
                'name' => '2025년 온라인 연구관리 전문교육',
                'period_start' => Carbon::parse('2025-11-01'),
                'period_end' => Carbon::parse('2025-11-30'),
                'period_time' => '자율학습',
                'target' => '연구관리 담당자',
                'completion_criteria' => '전체 강의 90% 이상 수강 + 설문조사 제출',
                'certificate_type' => '이수증',
                'completion_hours' => 20,
                'application_start' => Carbon::parse('2025-10-15 09:00:00'),
                'application_end' => Carbon::parse('2025-10-31 18:00:00'),
                'capacity' => 100,
                'capacity_unlimited' => false,
                'payment_methods' => ['온라인카드결제'],
                'fee' => 150000,
                'is_free' => false,
                'education_overview' => '<h4>교육명</h4><p>2025년 온라인 연구관리 전문교육</p><h4>목적</h4><p>연구관리 실무자를 위한 온라인 교육 프로그램</p>',
                'education_schedule' => '<p>수강기간: 2025.11.01 ~ 11.30 (1개월)</p><p>학습방식: 온라인 자율학습</p>',
                'fee_info' => '<p>수강료: 150,000원</p><p>결제방법: 온라인 카드결제</p>',
                'refund_policy' => '<p>강의 시작 전: 전액 환불</p><p>강의 시작 후 7일 이내: 50% 환불</p><p>7일 경과: 환불 불가</p>',
                'curriculum' => '<ul><li>연구비 관리 기초</li><li>연구과제 수행 실무</li><li>연구윤리 및 규정</li></ul>',
                'education_notice' => '<p>수료 기준: 전체 강의 90% 이상 수강 필수</p>',
            ],
        ];

        foreach ($onlineEducations as $education) {
            OnlineEducation::create($education);
        }
    }
}
