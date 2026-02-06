<?php

namespace App\Services\Backoffice;

use App\Models\EducationApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CertificationStatisticsService
{
    /**
     * 검색 조건에 따른 자격증 통계 집계 (자격증별)
     *
     * @return array{rows: array, filters: array}
     */
    public function getStatistics(Request $request): array
    {
        $filters = $this->parseFilters($request);
        $applications = EducationApplication::query()
            ->whereNotNull('certification_id')
            ->whereBetween('application_date', [$filters['start_date'], $filters['end_date']])
            ->with('certification')
            ->orderBy('application_date')
            ->get();

        $rows = $this->aggregateByCertification($applications);

        return [
            'rows' => $rows,
            'filters' => $filters,
        ];
    }

    /**
     * 요청에서 검색 조건 파싱 (연도/월 기본값, 기간 지정 시 해당 기간)
     */
    private function parseFilters(Request $request): array
    {
        $now = now();
        $year = (int) $request->get('year', $now->year);
        $month = $request->get('month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::parse($endDate)->endOfDay()->format('Y-m-d H:i:s');
        } else {
            $month = $month !== null && $month !== '' ? (int) $month : (int) $now->month;
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');
        }

        if ($month === null || $month === '') {
            $month = $now->month;
        }

        return [
            'year' => $year,
            'month' => (int) $month,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    /**
     * 자격증별 집계 (자격증명, 신청인원, 접수취소/반려, 합격, 불합격, 입금총액)
     *
     * @param Collection<int, EducationApplication> $applications
     * @return array<int, array{certification_name: string, application_count: int, cancelled_count: int, pass_count: int, fail_count: int, total_payment: float}>
     */
    private function aggregateByCertification(Collection $applications): array
    {
        $groups = [];

        foreach ($applications as $app) {
            if (!$app->certification_id || !$app->certification) {
                continue;
            }
            $key = 'c_' . $app->certification_id;
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'certification_name' => $app->certification->name ?? '',
                    'application_count' => 0,
                    'cancelled_count' => 0,
                    'pass_count' => 0,
                    'fail_count' => 0,
                    'total_payment' => 0.0,
                ];
            }
            if ($app->cancelled_at === null) {
                $groups[$key]['application_count']++;
            } else {
                $groups[$key]['cancelled_count']++;
            }
            $status = $app->qualification_display_status;
            if ($status === '합격') {
                $groups[$key]['pass_count']++;
            } elseif ($status === '불합격') {
                $groups[$key]['fail_count']++;
            }
            if (($app->payment_status ?? '') === '입금완료' && $app->participation_fee !== null) {
                $groups[$key]['total_payment'] += (float) $app->participation_fee;
            }
        }

        return array_values($groups);
    }

    /**
     * 엑셀(CSV) 다운로드용 집계 결과 반환
     */
    public function getStatisticsForExport(Request $request): array
    {
        $result = $this->getStatistics($request);
        return $result['rows'];
    }
}
