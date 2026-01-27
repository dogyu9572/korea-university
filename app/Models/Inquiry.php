<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'member_id',
        'category',
        'title',
        'content',
        'status',
        'views',
    ];

    protected $casts = [
        'views' => 'integer',
    ];

    /**
     * 회원 관계
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * 문의 첨부파일 관계
     */
    public function files()
    {
        return $this->hasMany(InquiryFile::class);
    }

    /**
     * 답변 관계 (1:1)
     */
    public function reply()
    {
        return $this->hasOne(InquiryReply::class);
    }

    /**
     * 분류별 필터링
     */
    public function scopeByCategory($query, $category)
    {
        if ($category && $category !== '전체') {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * 답변 상태별 필터링
     */
    public function scopeByStatus($query, $status)
    {
        if ($status && $status !== '전체' && $status !== '') {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * 등록일 범위 필터링
     */
    public function scopeByDateRange($query, $startDate = null, $endDate = null)
    {
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        return $query;
    }

    /**
     * 검색 (제목, 소속명, 작성자)
     */
    public function scopeSearch($query, $searchType, $searchTerm)
    {
        if (!$searchTerm || !$searchType || $searchType === '전체') {
            return $query;
        }

        switch ($searchType) {
            case '제목':
                return $query->where('title', 'like', '%' . $searchTerm . '%');
            case '소속명':
                return $query->whereHas('member', function($q) use ($searchTerm) {
                    $q->where('school_name', 'like', '%' . $searchTerm . '%');
                });
            case '작성자':
                return $query->whereHas('member', function($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            default:
                return $query;
        }
    }

    /**
     * 조회수 증가
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
}
