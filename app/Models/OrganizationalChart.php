<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationalChart extends Model
{
    protected $fillable = [
        'content',
    ];

    /**
     * 조직도 내용 조회 (단일 레코드만 유지)
     */
    public static function getContent()
    {
        $chart = self::first();
        return $chart ? $chart->content : null;
    }

    /**
     * 조직도 내용 저장/수정
     */
    public static function updateContent($content)
    {
        $chart = self::first();
        
        if ($chart) {
            $chart->content = $content;
            $chart->save();
        } else {
            self::create(['content' => $content]);
        }
        
        return $chart ?? self::first();
    }
}
