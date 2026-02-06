<?php

namespace App\Http\Controllers\Backoffice;

use App\Services\Backoffice\CertificationStatisticsService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificationStatisticsController extends BaseController
{
    public function __construct(
        private CertificationStatisticsService $certificationStatisticsService
    ) {
    }

    /**
     * 자격증 통계 메인 (검색 폼 + 결과 테이블)
     */
    public function index(Request $request)
    {
        $result = $this->certificationStatisticsService->getStatistics($request);
        $years = range(now()->year, now()->year - 5);
        $months = array_combine(range(1, 12), array_map(fn ($m) => $m . '월', range(1, 12)));

        return $this->view('backoffice.certification-statistics.index', [
            'rows' => $result['rows'],
            'filters' => $result['filters'],
            'years' => $years,
            'months' => $months,
        ]);
    }

    /**
     * 자격증 통계 엑셀(CSV) 다운로드
     */
    public function export(Request $request): StreamedResponse
    {
        $rows = $this->certificationStatisticsService->getStatisticsForExport($request);
        $filename = 'certification_statistics_' . date('YmdHis') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->streamDownload(function () use ($rows) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, [
                '자격증명', '신청인원(건)', '접수취소/반려 건수(건)', '합격인원(건)', '불합격인원(건)', '입금현황 총 금액(원)',
            ]);
            foreach ($rows as $row) {
                fputcsv($file, [
                    $row['certification_name'] ?? '',
                    $row['application_count'] ?? 0,
                    $row['cancelled_count'] ?? 0,
                    $row['pass_count'] ?? 0,
                    $row['fail_count'] ?? 0,
                    number_format((float) ($row['total_payment'] ?? 0)),
                ]);
            }
            fclose($file);
        }, $filename, $headers);
    }
}
