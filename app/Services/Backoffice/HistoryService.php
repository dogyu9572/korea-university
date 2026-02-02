<?php

namespace App\Services\Backoffice;

use App\Models\History;
use Carbon\Carbon;

class HistoryService
{
    /**
     * 연혁 목록 조회 (검색 조건 포함)
     */
    public function getHistories($year = null, $search = null)
    {
        return History::byYear($year)
            ->searchContent($search)
            ->orderedByDate()
            ->get();
    }

    /**
     * 연혁 생성
     */
    public function createHistory(array $data)
    {
        // 날짜에서 연도 자동 추출 (시작일 기준)
        if (isset($data['date'])) {
            $date = Carbon::parse($data['date']);
            $data['year'] = $date->year;
        }

        // date_end 빈 문자열이면 null로 저장
        if (isset($data['date_end']) && $data['date_end'] === '') {
            $data['date_end'] = null;
        }

        return History::create($data);
    }

    /**
     * 연혁 수정
     */
    public function updateHistory($id, array $data)
    {
        $history = History::findOrFail($id);

        // 날짜가 변경된 경우 연도 자동 추출 (시작일 기준)
        if (isset($data['date'])) {
            $date = Carbon::parse($data['date']);
            $data['year'] = $date->year;
        }

        // date_end 빈 문자열이면 null로 저장
        if (isset($data['date_end']) && $data['date_end'] === '') {
            $data['date_end'] = null;
        }

        $history->update($data);
        return $history;
    }

    /**
     * 연혁 삭제
     */
    public function deleteHistory($id)
    {
        $history = History::findOrFail($id);
        return $history->delete();
    }

    /**
     * 사용자 페이지용 연혁 목록 조회 (노출 항목만, 날짜 기준 정렬)
     */
    public function getVisibleHistories()
    {
        return History::visible()
            ->orderedByDate()
            ->get();
    }
}
