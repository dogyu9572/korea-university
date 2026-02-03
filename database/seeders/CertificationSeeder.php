<?php

namespace Database\Seeders;

use App\Models\Certification;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    public function run(): void
    {
        Certification::query()->delete();

        $certifications = [
            [
                'level' => '2급 자격증',
                'name' => '대학연구행정전문가 2급 자격시험',
                'exam_date' => Carbon::parse('2026-01-13'),
                'exam_method' => '필기시험 (객관식)',
                'passing_score' => 60,
                'eligibility' => '연구관리 실무 경력 2년 이상 또는 연구기관 종사자',
                'is_public' => true,
                'application_start' => Carbon::parse('2025-11-18 09:00:00'),
                'application_end' => Carbon::parse('2025-11-29 18:00:00'),
                'capacity' => 300,
                'capacity_unlimited' => false,
                'application_status' => '접수중',
                'payment_methods' => ['무통장입금', '온라인카드결제'],
                'deposit_account' => '농협은행 301-0334-8275-91',
                'deposit_deadline_days' => 3,
                'exam_overview' => '<h4>자격명</h4><p>대학연구행정전문가 2급 시험</p><h4>등급</h4><p>2급</p><h4>인증기관</h4><p>전국대학연구·산학협력관리자협회</p><h4>발급조건</h4><p>60점 이상 점수 충족 시 발급</p>',
                'exam_trend' => '<table><tr><th>과목</th><th>내용</th></tr><tr><td>1과목</td><td>연구비 관리, 과제관리, 연구윤리 등 연구행정 전반</td></tr><tr><td>2과목</td><td>과제 수행 실무, 정산 및 보고 업무 이해도 평가</td></tr></table>',
                'exam_venue' => '<h4>시험장 위치</h4><p>서울대학교 연구공원 지하 1층 시험센터</p><h4>교통 안내</h4><p>지하철 2호선 서울대입구역, 셔틀버스 운행</p>',
            ],
        ];

        foreach ($certifications as $certification) {
            Certification::create($certification);
        }
    }
}
