<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'title',
        'date',
        'date_end',
        'year',
        'content',
        'is_visible',
    ];

    protected $casts = [
        'date' => 'date',
        'date_end' => 'date',
    ];

    /**
     * 날짜 표시 (단일일: Y.m.d, 기간: Y.m.d~Y.m.d)
     */
    public function getDateDisplayAttribute(): string
    {
        $start = $this->date->format('Y.m.d');
        if (!$this->date_end || $this->date_end->eq($this->date)) {
            return $start;
        }
        return $start . '~' . $this->date_end->format('Y.m.d');
    }

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
