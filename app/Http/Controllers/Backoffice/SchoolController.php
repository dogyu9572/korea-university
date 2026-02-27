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

        // 연도 목록 (array_merge는 숫자 키를 0,1,2로 바꿔서 2026→0이 됨. + 로 키 유지)
        $years = ['전체' => '전체'] + $this->schoolService->getYears();

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

        $query = [];
        foreach (['page', 'per_page', 'branch', 'year', 'is_member_school', 'school_name'] as $key) {
            if ($request->has('_list_' . $key)) {
                $query[$key] = $request->input('_list_' . $key);
            }
        }

        return redirect()->route('backoffice.schools.index', $query)
            ->with('success', '학교 정보가 수정되었습니다.');
    }

    /**
     * 학교 삭제
     */
    public function destroy(Request $request, int $id)
    {
        $this->schoolService->deleteSchool($id);

        $query = [];
        foreach (['page', 'per_page', 'branch', 'year', 'is_member_school', 'school_name'] as $key) {
            if ($request->has('_list_' . $key)) {
                $query[$key] = $request->input('_list_' . $key);
            }
        }

        return redirect()->route('backoffice.schools.index', $query)
            ->with('success', '학교가 삭제되었습니다.');
    }

    /**
     * 엑셀 다운로드
     */
    public function export(Request $request)
    {
        $yearInput = $request->get('year', '전체');
        if ($yearInput === '' || $yearInput === 0 || $yearInput === '0') {
            $yearInput = '전체';
        }
        $filters = [
            'branch' => $request->get('branch', '전체'),
            'year' => $yearInput,
            'is_member_school' => $request->get('is_member_school', ''),
            'school_name' => $request->get('school_name', ''),
        ];

        $schools = $this->schoolService->exportSchoolsToExcel($filters);

        $filename = 'schools_' . date('YmdHis') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($schools) {
            $file = fopen('php://output', 'w');
            if ($file === false) {
                return;
            }
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['No', '연도', '학교명', '지회', '회원교', 'URL']);
            foreach ($schools as $index => $school) {
                fputcsv($file, [
                    $index + 1,
                    $school->year !== null ? (string) $school->year : '',
                    (string) ($school->school_name ?? ''),
                    (string) ($school->branch ?? ''),
                    $school->is_member_school ? 'Y' : 'N',
                    (string) ($school->url ?? ''),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
