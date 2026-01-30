<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberJoinRequest;
use App\Services\Backoffice\MemberService;
use App\Services\Backoffice\SchoolService;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /** 메뉴 메타 (gNum, sNum, gName, sName) - 회원 메뉴 공통 */
    private function memberMenuMeta(string $sName): array
    {
        return [
            'gNum' => '00',
            'sNum' => '00',
            'gName' => '회원',
            'sName' => $sName,
        ];
    }

    /** 회원가입 처리 */
    public function store(MemberJoinRequest $request, MemberService $memberService)
    {
        $memberService->createMember($request->getMemberData());
        return redirect()->route('member.join_end')->with('success', '회원가입이 완료되었습니다.');
    }

    /** 이메일 중복 확인 (AJAX) */
    public function checkEmail(Request $request, MemberService $memberService)
    {
        $email = $request->input('email');
        if (!$email) {
            return response()->json(['available' => false, 'message' => '이메일을 입력해 주세요.'], 400);
        }
        $exists = $memberService->checkDuplicateEmail($email, null);
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? '이미 사용 중인 이메일입니다.' : '사용 가능한 이메일입니다.',
        ]);
    }

    /** 휴대폰 중복 확인 (AJAX) */
    public function checkPhone(Request $request, MemberService $memberService)
    {
        $phone = $request->input('phone');
        if (!$phone) {
            return response()->json(['available' => false, 'message' => '휴대폰번호를 입력해 주세요.'], 400);
        }
        $exists = $memberService->checkDuplicatePhone($phone, null);
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? '이미 사용 중인 휴대폰번호입니다.' : '사용 가능한 휴대폰번호입니다.',
        ]);
    }

    /** 회원가입용 회원교 목록 (AJAX) */
    public function schools(Request $request, SchoolService $schoolService)
    {
        $schoolName = $request->get('school_name');
        $list = $schoolService->getMemberSchoolsForSelect($schoolName);
        return response()->json(['schools' => $list]);
    }

    public function login()
    {
        return view('member.login', $this->memberMenuMeta('로그인'));
    }

    public function join()
    {
        return view('member.join', $this->memberMenuMeta('회원가입'));
    }

    public function join_easy()
    {
        return view('member.join_easy', $this->memberMenuMeta('로그인'));
    }

    public function join_end()
    {
        return view('member.join_end', $this->memberMenuMeta('회원가입 완료'));
    }

    public function find_id()
    {
        return view('member.find_id', $this->memberMenuMeta('아이디/비밀번호 찾기'));
    }

    public function find_id_end()
    {
        return view('member.find_id_end', $this->memberMenuMeta('아이디 찾기 완료'));
    }

    public function find_pw()
    {
        return view('member.find_pw', $this->memberMenuMeta('아이디/비밀번호 찾기'));
    }

    public function find_pw_reset()
    {
        return view('member.find_pw_reset', $this->memberMenuMeta('비밀번호 재설정'));
    }

    public function find_pw_end()
    {
        return view('member.find_pw_end', $this->memberMenuMeta('비밀번호 변경 완료'));
    }

    public function pop_search_school()
    {
        return view('member.pop_search_school', $this->memberMenuMeta('학교 검색 팝업'));
    }
}
