<?php

namespace App\Services\Backoffice;

use App\Models\EducationApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class EducationStatisticsService
{
    /** 구분 옵션 (자격증 제외) */
    public const CATEGORY_ALL = '전체';
    public const CATEGORY_EDUCATION = '교육';
    public const CATEGORY_SEMINAR = '세미나';
    public const CATEGORY_OVERSEAS = '해외연수';
    public const CATEGORY_ONLINE = '온라인교육';

    /**
     * 검색 조건에 따른 교육 통계 집계 (프로그램별)
     *
     * @return array{rows: array, filters: array}
     */
    public function getStatistics(Request $request): array
    {
        $filters = $this->parseFilters($request);
        $query = EducationApplication::query()
            ->whereNull('certification_id')
            ->whereBetween('application_date', [$filters['start_date'], $filters['end_date']])
            ->with(['education', 'onlineEducation', 'seminarTraining']);

        $this->applyCategoryFilter($query, $filters['category']);

        $applications = $query->orderBy('application_date')->get();
        $rows = $this->aggregateByProgram($applications);

        return [
            'rows' => $rows,
            'filters' => $filters,
        ];
    }

    /**
     * 요청에서 검색 조건 파싱
     */
    private function parseFilters(Request $request): array
    {
        $category = $request->get('category', self::CATEGORY_ALL);
        $category = in_array($category, [
            self::CATEGORY_ALL, self::CATEGORY_EDUCATION, self::CATEGORY_SEMINAR,
            self::CATEGORY_OVERSEAS, self::CATEGORY_ONLINE,
        ], true) ? $category : self::CATEGORY_ALL;

        $now = now();
        $fiscalYear = $request->boolean('fiscal_year');
        $year = (int) $request->get('year', $now->year);
        $month = $request->get('month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($fiscalYear && $year) {
            $startDate = Carbon::parse("{$year}-01-01")->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::parse("{$year}-12-31")->endOfDay()->format('Y-m-d H:i:s');
        } elseif ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::parse($endDate)->endOfDay()->format('Y-m-d H:i:s');
        } else {
            $month = $month !== null && $month !== '' ? (int) $month : (int) $now->month;
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');
        }

        if (!isset($month) || $month === '' || $month === null) {
            $month = $now->month;
        }

        return [
            'category' => $category,
            'year' => $year,
            'month' => (int) $month,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'fiscal_year' => $fiscalYear,
        ];
    }

    private function applyCategoryFilter($query, string $category): void
    {
        if ($category === self::CATEGORY_ALL) {
            return;
        }
        if ($category === self::CATEGORY_EDUCATION) {
            $query->whereNotNull('education_id');
            return;
        }
        if ($category === self::CATEGORY_ONLINE) {
            $query->whereNotNull('online_education_id');
            return;
        }
        if ($category === self::CATEGORY_SEMINAR) {
            $query->whereNotNull('seminar_training_id')
                ->whereHas('seminarTraining', fn ($q) => $q->where('type', '세미나'));
            return;
        }
        if ($category === self::CATEGORY_OVERSEAS) {
            $query->whereNotNull('seminar_training_id')
                ->whereHas('seminarTraining', fn ($q) => $q->where('type', '해외연수'));
            return;
        }
    }

    /**
     * 프로그램별 집계 (구분, 교육구분, 교육명, 신청건수, 접수취소/반려, 수료/이수, 입금총액)
     *
     * @param Collection<int, EducationApplication> $applications
     * @return array<int, array{category_label: string, education_type_label: string, program_name: string, application_count: int, cancelled_count: int, completed_count: int, total_payment: float}>
     */
    private function aggregateByProgram(Collection $applications): array
    {
        $groups = [];

        foreach ($applications as $app) {
            $key = $this->programKey($app);
            if ($key === null) {
                continue;
            }
            if (!isset($groups[$key])) {
                $groups[$key] = [
                    'category_label' => $this->categoryLabel($app),
                    'education_type_label' => $this->educationTypeLabel($app),
                    'program_name' => $this->programName($app),
                    'application_count' => 0,
                    'cancelled_count' => 0,
                    'completed_count' => 0,
                    'total_payment' => 0,
                ];
            }
            if ($app->cancelled_at === null) {
                $groups[$key]['application_count']++;
            } else {
                $groups[$key]['cancelled_count']++;
            }
            if ($app->is_completed) {
                $groups[$key]['completed_count']++;
            }
            if (($app->payment_status ?? '') === '입금완료' && $app->participation_fee !== null) {
                $groups[$key]['total_payment'] += (float) $app->participation_fee;
            }
        }

        return array_values($groups);
    }

    private function programKey(EducationApplication $app): ?string
    {
        if ($app->education_id !== null) {
            return 'e_' . $app->education_id;
        }
        if ($app->online_education_id !== null) {
            return 'o_' . $app->online_education_id;
        }
        if ($app->seminar_training_id !== null && $app->relationLoaded('seminarTraining') && $app->seminarTraining) {
            return 's_' . $app->seminar_training_id . '_' . ($app->seminarTraining->type ?? '');
        }
        return null;
    }

    private function categoryLabel(EducationApplication $app): string
    {
        if ($app->education_id !== null) {
            return self::CATEGORY_EDUCATION;
        }
        if ($app->online_education_id !== null) {
            return self::CATEGORY_ONLINE;
        }
        if ($app->seminar_training_id !== null && $app->seminarTraining) {
            return $app->seminarTraining->type === '해외연수' ? self::CATEGORY_OVERSEAS : self::CATEGORY_SEMINAR;
        }
        return '';
    }

    private function educationTypeLabel(EducationApplication $app): string
    {
        if ($app->education_id !== null && $app->education) {
            return $app->education->education_type ?? self::CATEGORY_EDUCATION;
        }
        if ($app->online_education_id !== null) {
            return self::CATEGORY_ONLINE;
        }
        if ($app->seminar_training_id !== null && $app->seminarTraining) {
            return $app->seminarTraining->type ?? '';
        }
        return '';
    }

    private function programName(EducationApplication $app): string
    {
        if ($app->education_id !== null && $app->education) {
            return $app->education->name ?? '';
        }
        if ($app->online_education_id !== null && $app->onlineEducation) {
            return $app->onlineEducation->name ?? '';
        }
        if ($app->seminar_training_id !== null && $app->seminarTraining) {
            return $app->seminarTraining->name ?? '';
        }
        return '';
    }

    /**
     * 통계 결과를 CSV 스트리밍용 배열로 반환 (헤더 + 행)
     */
    public function getStatisticsForExport(Request $request): array
    {
        $result = $this->getStatistics($request);
        return $result['rows'];
    }
}
