<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationalMember extends Model
{
    protected $fillable = [
        'category',
        'name',
        'position',
        'affiliation',
        'phone',
        'sort_order',
    ];

    /**
     * 정렬된 구성원 조회 스코프
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'desc')->orderBy('created_at', 'desc');
    }

    /**
     * 카테고리별 조회 스코프
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * 카테고리별 그룹화된 구성원 조회
     */
    public static function getGroupedByCategory()
    {
        return self::ordered()
            ->get()
            ->groupBy('category');
    }
}
