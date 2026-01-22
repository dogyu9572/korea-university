<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\HistoryRequest;
use App\Services\Backoffice\HistoryService;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    protected $historyService;

    public function __construct(HistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    /**
     * 연혁 관리 페이지 표시
     */
    public function index(Request $request)
    {
        $year = $request->get('year', '전체');
        $search = $request->get('search', '');
        
        $histories = $this->historyService->getHistories($year, $search);
        
        // 연도 목록 조회 (검색용)
        $years = \App\Models\History::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return view('backoffice.history.index', compact('histories', 'year', 'search', 'years'));
    }

    /**
     * 연혁 추가
     */
    public function store(HistoryRequest $request)
    {
        $this->historyService->createHistory($request->validated());

        return redirect()->route('backoffice.history.index')
            ->with('success', '연혁이 추가되었습니다.');
    }

    /**
     * 연혁 수정
     */
    public function update(HistoryRequest $request, $id)
    {
        $this->historyService->updateHistory($id, $request->validated());

        return redirect()->route('backoffice.history.index')
            ->with('success', '연혁이 수정되었습니다.');
    }

    /**
     * 연혁 삭제
     */
    public function destroy($id)
    {
        $this->historyService->deleteHistory($id);

        return redirect()->route('backoffice.history.index')
            ->with('success', '연혁이 삭제되었습니다.');
    }
}
