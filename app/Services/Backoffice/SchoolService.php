<?php

namespace App\Services\Backoffice;

use App\Models\School;
use Illuminate\Pagination\LengthAwarePaginator;

class SchoolService
{
    /**
     * 지회 목록 반환
     */
    public function getBranches(): array
    {
        return [
            '서울지회',
            '경기인천강원지회',
            '대전충청세종지회',
            '대구경북지회',
            '부산울산경남지회',
            '전북지회',
            '광주전남제주지회',
        ];
    }

    /**
     * 학교 목록 조회 (검색/필터링/페이지네이션)
     */
    public function getSchools(array $filters = [], int $perPage = 20)
    {
        $query = School::query();

        // 지회 필터
        if (isset($filters['branch']) && $filters['branch']) {
            $query->byBranch($filters['branch']);
        }

        // 연도 필터
        if (isset($filters['year']) && $filters['year']) {
            $query->byYear($filters['year']);
        }

        // 회원교 여부 필터
        if (isset($filters['is_member_school']) && $filters['is_member_school'] !== '') {
            $query->byMemberSchool($filters['is_member_school']);
        }

        // 학교명 검색
        if (isset($filters['school_name']) && $filters['school_name']) {
            $query->searchByName($filters['school_name']);
        }

        // 정렬 (연도 내림차순, 생성일 내림차순)
        $query->orderBy('year', 'desc')
              ->orderBy('created_at', 'desc');

        // 페이지네이션
        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 학교 상세 조회
     */
    public function getSchool(int $id)
    {
        return School::findOrFail($id);
    }

    /**
     * 학교 등록
     */
    public function createSchool(array $data)
    {
        // is_member_school을 boolean으로 변환
        if (isset($data['is_member_school'])) {
            $data['is_member_school'] = $data['is_member_school'] === 'Y' || $data['is_member_school'] === true || $data['is_member_school'] === '1';
        }

        return School::create($data);
    }

    /**
     * 학교 정보 수정
     */
    public function updateSchool(int $id, array $data)
    {
        $school = School::findOrFail($id);

        // is_member_school을 boolean으로 변환
        if (isset($data['is_member_school'])) {
            $data['is_member_school'] = $data['is_member_school'] === 'Y' || $data['is_member_school'] === true || $data['is_member_school'] === '1';
        }

        $school->update($data);
        return $school;
    }

    /**
     * 학교 삭제
     */
    public function deleteSchool(int $id)
    {
        $school = School::findOrFail($id);
        $school->delete();
        return $school;
    }

    /**
     * 엑셀 다운로드용 데이터 조회
     */
    public function exportSchoolsToExcel(array $filters = [])
    {
        $query = School::query();

        // 필터 적용 (getSchools와 동일한 로직)
        if (isset($filters['branch']) && $filters['branch']) {
            $query->byBranch($filters['branch']);
        }

        if (isset($filters['year']) && $filters['year']) {
            $query->byYear($filters['year']);
        }

        if (isset($filters['is_member_school']) && $filters['is_member_school'] !== '') {
            $query->byMemberSchool($filters['is_member_school']);
        }

        if (isset($filters['school_name']) && $filters['school_name']) {
            $query->searchByName($filters['school_name']);
        }

        $query->orderBy('year', 'desc')
              ->orderBy('created_at', 'desc');

        return $query->get();
    }

    /**
     * 회원가입용 회원교 목록 (회원교만, 검색 옵션)
     */
    public function getMemberSchoolsForSelect(?string $schoolName = null)
    {
        $query = School::query()
            ->where('is_member_school', true)
            ->orderBy('school_name');

        if ($schoolName !== null && $schoolName !== '') {
            $query->searchByName($schoolName);
        }

        return $query->get(['id', 'school_name', 'branch', 'year']);
    }

    /**
     * 연도 목록 생성 (1970년부터 현재 연도까지)
     */
    public function getYears(): array
    {
        $currentYear = (int) date('Y');
        $years = [];
        
        for ($year = $currentYear; $year >= 1970; $year--) {
            $years[$year] = $year;
        }
        
        return $years;
    }
}
