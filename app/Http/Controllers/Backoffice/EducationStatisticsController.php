<?php

namespace App\Http\Controllers\Backoffice;

use App\Services\Backoffice\EducationStatisticsService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EducationStatisticsController extends BaseController
{
    public function __construct(
        private EducationStatisticsService $educationStatisticsService
    ) {
    }

    /**
     * 교육 통계 메인 (검색 폼 + 결과 테이블)
     */
    public function index(Request $request)
    {
        $result = $this->educationStatisticsService->getStatistics($request);
        $years = range(now()->year, now()->year - 5);
        $months = array_combine(range(1, 12), array_map(fn ($m) => $m . '월', range(1, 12)));

        return $this->view('backoffice.education-statistics.index', [
            'rows' => $result['rows'],
            'filters' => $result['filters'],
            'years' => $years,
            'months' => $months,
            'categories' => [
                \App\Services\Backoffice\EducationStatisticsService::CATEGORY_ALL => '전체',
                \App\Services\Backoffice\EducationStatisticsService::CATEGORY_EDUCATION => '교육',
                \App\Services\Backoffice\EducationStatisticsService::CATEGORY_SEMINAR => '세미나',
                \App\Services\Backoffice\EducationStatisticsService::CATEGORY_OVERSEAS => '해외연수',
                \App\Services\Backoffice\EducationStatisticsService::CATEGORY_ONLINE => '온라인교육',
            ],
        ]);
    }

    /**
     * 교육 통계 엑셀(CSV) 다운로드
     */
    public function export(Request $request): StreamedResponse
    {
        $rows = $this->educationStatisticsService->getStatisticsForExport($request);
        $filename = 'education_statistics_' . date('YmdHis') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->streamDownload(function () use ($rows) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, [
                '구분', '교육구분', '교육명', '신청건수(건)', '접수취소/반려 건수(건)', '수료/이수 건 수(건)', '입금현황 총 금액(원)',
            ]);
            foreach ($rows as $row) {
                fputcsv($file, [
                    $row['category_label'] ?? '',
                    $row['education_type_label'] ?? '',
                    $row['program_name'] ?? '',
                    $row['application_count'] ?? 0,
                    $row['cancelled_count'] ?? 0,
                    $row['completed_count'] ?? 0,
                    number_format((float) ($row['total_payment'] ?? 0)),
                ]);
            }
            fclose($file);
        }, $filename, $headers);
    }
}
