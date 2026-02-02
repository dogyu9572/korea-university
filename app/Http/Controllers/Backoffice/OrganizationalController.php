<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationalChartRequest;
use App\Http\Requests\OrganizationalMemberRequest;
use App\Services\Backoffice\OrganizationalService;
use Illuminate\Http\Request;

class OrganizationalController extends Controller
{
    protected $organizationalService;

    public function __construct(OrganizationalService $organizationalService)
    {
        $this->organizationalService = $organizationalService;
    }

    /**
     * 조직도 관리 페이지 표시
     */
    public function index()
    {
        $chartContent = $this->organizationalService->getChartContent();
        $members = $this->organizationalService->getMembers();

        return view('backoffice.organizational.index', compact('chartContent', 'members'));
    }

    /**
     * 조직도 내용 저장/수정
     */
    public function updateChart(OrganizationalChartRequest $request)
    {
        $this->organizationalService->updateChartContent($request->content);

        return redirect()->route('backoffice.organizational.index')
            ->with('success', '조직도 내용이 저장되었습니다.');
    }

    /**
     * 구성원 추가
     */
    public function storeMember(OrganizationalMemberRequest $request)
    {
        $this->organizationalService->createMember($request->validated());

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => '구성원이 추가되었습니다.']);
        }

        return redirect()->route('backoffice.organizational.index')
            ->with('success', '구성원이 추가되었습니다.');
    }

    /**
     * 구성원 수정
     */
    public function updateMember(OrganizationalMemberRequest $request, $id)
    {
        $this->organizationalService->updateMember($id, $request->validated());

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => '구성원 정보가 수정되었습니다.']);
        }

        return redirect()->route('backoffice.organizational.index')
            ->with('success', '구성원 정보가 수정되었습니다.');
    }

    /**
     * 구성원 삭제
     */
    public function destroyMember(Request $request, $id)
    {
        $this->organizationalService->deleteMember($id);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => '구성원이 삭제되었습니다.']);
        }

        return redirect()->route('backoffice.organizational.index')
            ->with('success', '구성원이 삭제되었습니다.');
    }

    /**
     * 정렬 순서 업데이트 (AJAX)
     */
    public function updateOrder(Request $request)
    {
        $memberOrder = $request->input('memberOrder', []);

        $this->organizationalService->updateMemberOrder($memberOrder);

        return response()->json(['success' => true]);
    }
}
