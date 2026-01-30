<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequest;
use App\Services\Backoffice\MemberService;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    /**
     * 회원 목록 페이지
     */
    public function index(Request $request)
    {
        $filters = [
            'join_type' => $request->get('join_type', '전체'),
            'join_date_start' => $request->get('join_date_start'),
            'join_date_end' => $request->get('join_date_end'),
            'marketing_consent' => $request->get('marketing_consent', []),
            'search_type' => $request->get('search_type', '전체'),
            'search_term' => $request->get('search_term', ''),
        ];

        $perPage = $request->get('per_page', 20);
        $members = $this->memberService->getMembers($filters, $perPage);

        // 가입 구분 목록
        $joinTypes = ['전체' => '전체', 'email' => '이메일', 'kakao' => '카카오', 'naver' => '네이버'];

        return view('backoffice.members.index', compact('members', 'filters', 'joinTypes', 'perPage'));
    }

    /**
     * 회원 등록 페이지
     */
    public function create()
    {
        return view('backoffice.members.create');
    }

    /**
     * 회원 등록 처리
     */
    public function store(MemberRequest $request)
    {
        $this->memberService->createMember($request->validated());

        return redirect()->route('backoffice.members.index')
            ->with('success', '회원이 등록되었습니다.');
    }

    /**
     * 회원 상세 페이지
     */
    public function show(int $id)
    {
        $member = $this->memberService->getMember($id);
        return view('backoffice.members.show', compact('member'));
    }

    /**
     * 회원 수정 페이지
     */
    public function edit(int $id)
    {
        $member = $this->memberService->getMember($id);
        return view('backoffice.members.edit', compact('member'));
    }

    /**
     * 회원 수정 처리
     */
    public function update(MemberRequest $request, int $id)
    {
        $this->memberService->updateMember($id, $request->validated());

        return redirect()->route('backoffice.members.index')
            ->with('success', '회원 정보가 수정되었습니다.');
    }

    /**
     * 회원 삭제 (soft delete)
     */
    public function destroy(int $id)
    {
        $this->memberService->deleteMember($id);

        return redirect()->route('backoffice.members.index')
            ->with('success', '회원이 삭제되었습니다.');
    }

    /**
     * 회원 일괄 삭제
     */
    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => '선택된 회원이 없습니다.'], 400);
        }

        $this->memberService->deleteMembers($ids);

        return response()->json(['success' => true, 'message' => '선택된 회원이 삭제되었습니다.']);
    }

    /**
     * 엑셀 다운로드
     */
    public function export(Request $request)
    {
        $filters = [
            'join_type' => $request->get('join_type', '전체'),
            'join_date_start' => $request->get('join_date_start'),
            'join_date_end' => $request->get('join_date_end'),
            'marketing_consent' => $request->get('marketing_consent', []),
            'search_type' => $request->get('search_type', '전체'),
            'search_term' => $request->get('search_term', ''),
        ];

        $members = $this->memberService->exportMembersToExcel($filters);

        // 간단한 CSV 형식으로 다운로드 (필요시 Laravel Excel 라이브러리 사용)
        $filename = 'members_' . date('YmdHis') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($members) {
            $file = fopen('php://output', 'w');
            
            // BOM 추가 (한글 깨짐 방지)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // 헤더
            fputcsv($file, ['No', '가입구분', 'ID', '학교명', '이름', '휴대폰번호', '이메일주소', '학교 대표자', '가입일시']);
            
            // 데이터
            foreach ($members as $index => $member) {
                fputcsv($file, [
                    $index + 1,
                    $member->join_type === 'email' ? '이메일' : ($member->join_type === 'kakao' ? '카카오' : '네이버'),
                    $member->login_id ?? '',
                    $member->school_name,
                    $member->name,
                    $member->phone_number,
                    $member->email ?? '',
                    $member->is_school_representative ? 'Y' : 'N',
                    $member->created_at->format('Y.m.d H:i'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * 이메일 중복 확인 (AJAX)
     */
    public function checkDuplicateEmail(Request $request)
    {
        $email = $request->input('email');
        $excludeId = $request->input('exclude_id');

        if (!$email) {
            return response()->json(['available' => false, 'message' => '이메일을 입력해주세요.'], 400);
        }

        $exists = $this->memberService->checkDuplicateEmail($email, $excludeId);

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? '이미 사용 중인 이메일입니다.' : '사용 가능한 이메일입니다.'
        ]);
    }

    /**
     * 휴대폰 중복 확인 (AJAX)
     */
    public function checkDuplicatePhone(Request $request)
    {
        $phone = $request->input('phone');
        $excludeId = $request->input('exclude_id');

        if (!$phone) {
            return response()->json(['available' => false, 'message' => '휴대폰번호를 입력해주세요.'], 400);
        }

        $exists = $this->memberService->checkDuplicatePhone($phone, $excludeId);

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? '이미 사용 중인 휴대폰번호입니다.' : '사용 가능한 휴대폰번호입니다.'
        ]);
    }

    /**
     * 탈퇴회원 목록 페이지
     */
    public function withdrawn(Request $request)
    {
        $filters = [
            'join_type' => $request->get('join_type', '전체'),
            'withdrawal_date_start' => $request->get('withdrawal_date_start'),
            'withdrawal_date_end' => $request->get('withdrawal_date_end'),
            'search_type' => $request->get('search_type', '전체'),
            'search_term' => $request->get('search_term', ''),
        ];

        $perPage = $request->get('per_page', 20);
        $members = $this->memberService->getWithdrawnMembers($filters, $perPage);

        // 가입 구분 목록
        $joinTypes = ['전체' => '전체', 'email' => '이메일', 'kakao' => '카카오', 'naver' => '네이버'];

        return view('backoffice.members.withdrawn', compact('members', 'filters', 'joinTypes', 'perPage'));
    }

    /**
     * 탈퇴회원 복원
     */
    public function restore(Request $request, int $id)
    {
        $this->memberService->restoreMember($id);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '회원이 복원되었습니다.',
                'redirect' => route('backoffice.members.index'),
            ]);
        }

        return redirect()->route('backoffice.members.index')
            ->with('success', '회원이 복원되었습니다.');
    }

    /**
     * 탈퇴회원 영구 삭제
     */
    public function forceDelete(int $id)
    {
        $this->memberService->forceDeleteMember($id);

        return redirect()->route('backoffice.withdrawn')
            ->with('success', '회원이 영구 삭제되었습니다.');
    }

    /**
     * 탈퇴회원 일괄 영구 삭제
     */
    public function forceDeleteMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => '선택된 회원이 없습니다.'], 400);
        }

        $this->memberService->forceDeleteMembers($ids);

        return response()->json(['success' => true, 'message' => '선택된 회원이 영구 삭제되었습니다.']);
    }
}
