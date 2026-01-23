<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'branch',
        'year',
        'school_name',
        'is_member_school',
        'url',
    ];

    protected $casts = [
        'is_member_school' => 'boolean',
        'year' => 'integer',
    ];

    /**
     * 지회별 필터링
     */
    public function scopeByBranch($query, $branch)
    {
        if ($branch && $branch !== '전체') {
            return $query->where('branch', $branch);
        }
        return $query;
    }

    /**
     * 연도별 필터링
     */
    public function scopeByYear($query, $year)
    {
        if ($year && $year !== '전체') {
            return $query->where('year', $year);
        }
        return $query;
    }

    /**
     * 회원교 여부 필터링
     */
    public function scopeByMemberSchool($query, $isMemberSchool)
    {
        if ($isMemberSchool !== null && $isMemberSchool !== '') {
            $value = $isMemberSchool === 'Y' || $isMemberSchool === true || $isMemberSchool === '1';
            return $query->where('is_member_school', $value);
        }
        return $query;
    }

    /**
     * 학교명 검색
     */
    public function scopeSearchByName($query, $schoolName)
    {
        if ($schoolName) {
            return $query->where('school_name', 'like', '%' . $schoolName . '%');
        }
        return $query;
    }
}
