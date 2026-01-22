<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'date',
        'year',
        'content',
        'is_visible',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * 날짜 기준 내림차순 정렬 스코프
     */
    public function scopeOrderedByDate($query)
    {
        return $query->orderBy('date', 'desc');
    }

    /**
     * 노출 항목만 조회 스코프
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', 'Y');
    }

    /**
     * 연도별 조회 스코프
     */
    public function scopeByYear($query, $year)
    {
        if ($year && $year !== '전체') {
            return $query->where('year', $year);
        }
        return $query;
    }

    /**
     * 내용 검색 스코프
     */
    public function scopeSearchContent($query, $search)
    {
        if ($search) {
            return $query->where('content', 'like', '%' . $search . '%');
        }
        return $query;
    }
}
