<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberFindIdRequest;
use App\Http\Requests\MemberFindPwRequest;
use App\Http\Requests\MemberFindPwResetRequest;
use App\Http\Requests\MemberJoinRequest;
use App\Http\Requests\MemberLoginRequest;
use App\Models\Member;
use App\Services\Backoffice\MemberService;
use App\Services\Backoffice\SchoolService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

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

    /** 로그인 폼 */
    public function login()
    {
        $remembered = request()->cookie('remembered_email');
        if ($remembered && (str_contains($remembered, '=') || str_contains($remembered, 'expires') || strlen($remembered) > 100)) {
            $remembered = '';
        }
        return view('member.login', array_merge($this->memberMenuMeta('로그인'), [
            'remembered_email_value' => $remembered ?: '',
        ]));
    }

    /** 로그인 처리 */
    public function postLogin(MemberLoginRequest $request)
    {
        $loginIdOrEmail = trim($request->input('email'));
        $password = $request->input('password');

        $member = Member::active()
            ->where(function ($q) use ($loginIdOrEmail) {
                $q->where('login_id', $loginIdOrEmail)->orWhere('email', $loginIdOrEmail);
            })
            ->first();

        if (!$member || !$member->password) {
            return back()->withErrors(['email' => '이메일(아이디) 또는 비밀번호가 일치하지 않습니다.'])->withInput($request->only('email'));
        }

        if (!Hash::check($password, $member->password)) {
            return back()->withErrors(['email' => '이메일(아이디) 또는 비밀번호가 일치하지 않습니다.'])->withInput($request->only('email'));
        }

        Auth::guard('member')->login($member, false);
        $request->session()->regenerate();

        $redirect = redirect()->intended(route('mypage.application_status'));
        if ($request->boolean('remember')) {
            $redirect->withCookie(cookie('remembered_email', $loginIdOrEmail, 60 * 24 * 365, '/', null, false, true, false, 'lax'));
        } else {
            $redirect->withCookie(Cookie::forget('remembered_email', '/'));
        }

        return $redirect;
    }

    /** 로그아웃 */
    public function logout(Request $request)
    {
        Auth::guard('member')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('member.login')->with('success', '로그아웃되었습니다.');
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

    /** 아이디 찾기 처리 */
    public function postFindId(MemberFindIdRequest $request)
    {
        $name = trim($request->input('name'));
        $phone = Member::normalizePhone($request->input('phone_number'));

        $member = Member::active()
            ->where('join_type', 'email')
            ->where('name', $name)
            ->where('phone_number', $phone)
            ->first();

        if (!$member) {
            return back()->withErrors(['phone_number' => '일치하는 회원 정보가 없습니다.'])->withInput($request->only('name', 'phone_number'));
        }

        $masked = Member::maskLoginId($member->login_id);

        return redirect()->route('member.find_id_end')->with('found_login_id', $masked);
    }

    public function find_id_end()
    {
        $found_login_id = session('found_login_id');
        if ($found_login_id === null || $found_login_id === '') {
            return redirect()->route('member.find_id');
        }

        session()->forget('found_login_id');

        return view('member.find_id_end', array_merge($this->memberMenuMeta('아이디 찾기 완료'), [
            'found_login_id' => $found_login_id,
        ]));
    }

    public function find_pw()
    {
        return view('member.find_pw', $this->memberMenuMeta('아이디/비밀번호 찾기'));
    }

    /** 비밀번호 찾기 본인 확인 처리 */
    public function postFindPw(MemberFindPwRequest $request)
    {
        $email = trim($request->input('email'));
        $phone = Member::normalizePhone($request->input('phone_number'));
        $name = trim($request->input('name'));

        $member = Member::active()
            ->where('join_type', 'email')
            ->where('email', $email)
            ->where('phone_number', $phone)
            ->where('name', $name)
            ->first();

        if (!$member) {
            return back()->withErrors(['email' => '일치하는 회원 정보가 없습니다.'])->withInput($request->only('email', 'phone_number', 'name'));
        }

        session(['pw_reset_member_id' => $member->id]);

        return redirect()->route('member.find_pw_reset');
    }

    public function find_pw_reset()
    {
        if (!session('pw_reset_member_id')) {
            return redirect()->route('member.find_pw');
        }

        return view('member.find_pw_reset', $this->memberMenuMeta('비밀번호 재설정'));
    }

    /** 비밀번호 재설정 처리 */
    public function postFindPwReset(MemberFindPwResetRequest $request)
    {
        $memberId = session('pw_reset_member_id');
        if (!$memberId) {
            return redirect()->route('member.find_pw');
        }

        $member = Member::active()->find($memberId);
        if (!$member) {
            session()->forget('pw_reset_member_id');
            return redirect()->route('member.find_pw');
        }

        $member->update(['password' => Hash::make($request->password)]);
        session()->forget('pw_reset_member_id');

        return redirect()->route('member.find_pw_end');
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
