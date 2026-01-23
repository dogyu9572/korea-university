<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Requests\Backoffice\SchoolRequest;
use App\Services\Backoffice\SchoolService;
use Illuminate\Http\Request;

class SchoolController extends BaseController
{
    protected $schoolService;

    public function __construct(SchoolService $schoolService)
    {
        $this->schoolService = $schoolService;
    }

    /**
     * 학교 목록 페이지
     */
    public function index(Request $request)
    {
        $filters = [
            'branch' => $request->get('branch', '전체'),
            'year' => $request->get('year', '전체'),
            'is_member_school' => $request->get('is_member_school', ''),
            'school_name' => $request->get('school_name', ''),
        ];

        $perPage = $request->get('per_page', 20);
        $schools = $this->schoolService->getSchools($filters, $perPage);

        // 지회 목록
        $branches = $this->schoolService->getBranches();
        $branches = array_merge(['전체' => '전체'], array_combine($branches, $branches));

        // 연도 목록
        $years = $this->schoolService->getYears();
        $years = array_merge(['전체' => '전체'], $years);

        return $this->view('backoffice.schools.index', compact('schools', 'filters', 'branches', 'years', 'perPage'));
    }

    /**
     * 학교 등록 페이지
     */
    public function create()
    {
        // 지회 목록
        $branches = $this->schoolService->getBranches();
        $branches = array_combine($branches, $branches);

        // 연도 목록
        $years = $this->schoolService->getYears();

        return $this->view('backoffice.schools.create', compact('branches', 'years'));
    }

    /**
     * 학교 등록 처리
     */
    public function store(SchoolRequest $request)
    {
        $this->schoolService->createSchool($request->validated());

        return redirect()->route('backoffice.schools.index')
            ->with('success', '학교가 등록되었습니다.');
    }

    /**
     * 학교 수정 페이지
     */
    public function edit(int $id)
    {
        $school = $this->schoolService->getSchool($id);

        // 지회 목록
        $branches = $this->schoolService->getBranches();
        $branches = array_combine($branches, $branches);

        // 연도 목록
        $years = $this->schoolService->getYears();

        return $this->view('backoffice.schools.edit', compact('school', 'branches', 'years'));
    }

    /**
     * 학교 수정 처리
     */
    public function update(SchoolRequest $request, int $id)
    {
        $this->schoolService->updateSchool($id, $request->validated());

        return redirect()->route('backoffice.schools.index')
            ->with('success', '학교 정보가 수정되었습니다.');
    }

    /**
     * 학교 삭제
     */
    public function destroy(int $id)
    {
        $this->schoolService->deleteSchool($id);

        return redirect()->route('backoffice.schools.index')
            ->with('success', '학교가 삭제되었습니다.');
    }

    /**
     * 엑셀 다운로드
     */
    public function export(Request $request)
    {
        $filters = [
            'branch' => $request->get('branch', '전체'),
            'year' => $request->get('year', '전체'),
            'is_member_school' => $request->get('is_member_school', ''),
            'school_name' => $request->get('school_name', ''),
        ];

        $schools = $this->schoolService->exportSchoolsToExcel($filters);

        // CSV 형식으로 다운로드
        $filename = 'schools_' . date('YmdHis') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($schools) {
            $file = fopen('php://output', 'w');
            
            // BOM 추가 (한글 깨짐 방지)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // 헤더
            fputcsv($file, ['No', '연도', '학교명', '지회', '회원교', 'URL']);
            
            // 데이터
            foreach ($schools as $index => $school) {
                fputcsv($file, [
                    $index + 1,
                    $school->year,
                    $school->school_name,
                    $school->branch,
                    $school->is_member_school ? 'Y' : 'N',
                    $school->url ?? '',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
