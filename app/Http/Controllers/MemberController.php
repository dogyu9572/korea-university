<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberFindIdRequest;
use App\Http\Requests\MemberFindPwRequest;
use App\Http\Requests\MemberFindPwResetRequest;
use App\Http\Requests\MemberJoinRequest;
use App\Http\Requests\MemberLoginRequest;
use App\Http\Requests\MemberSnsJoinRequest;
use App\Models\Member;
use Illuminate\Auth\Access\AuthorizationException;
use App\Services\Backoffice\MemberService;
use App\Services\Backoffice\SchoolService;
use App\Services\MemberAuthService;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

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
    public function store(Request $request, MemberService $memberService)
    {
        $joinType = $request->input('join_type', 'email');

        if (in_array($joinType, ['naver', 'kakao'])) {
            try {
                $snsRequest = MemberSnsJoinRequest::createFrom($request)->replace($request->all());
                $snsRequest->setContainer(app())->setRedirector(app()->make(Redirector::class));
                $snsRequest->validateResolved();
            } catch (ValidationException $e) {
                return redirect()->back()->withInput()->withErrors($e->errors());
            } catch (AuthorizationException $e) {
                return redirect()->back()->withInput()->withErrors([
                    'join_type' => '세션이 만료되었습니다. SNS 로그인을 다시 시도해 주세요.',
                ]);
            }
            $member = $memberService->createMember($snsRequest->getMemberData());
            session()->forget('sns_join_data');
            Auth::guard('member')->login($member, false);
            $request->session()->regenerate();
            return redirect()->route('member.join_end')->with('success', '회원가입이 완료되었습니다.');
        }

        try {
            $memberJoinRequest = MemberJoinRequest::createFrom($request)->replace($request->all());
            $memberJoinRequest->setContainer(app())->setRedirector(app()->make(Redirector::class));
            $memberJoinRequest->validateResolved();
        } catch (ValidationException $e) {
            return redirect()->back()->withInput($request->except('_token'))->withErrors($e->errors());
        }
        $memberService->createMember($memberJoinRequest->getMemberData());
        return redirect()->route('member.join_end')->with('success', '회원가입이 완료되었습니다.');
    }

    /** 이메일 중복 확인 (AJAX) - 로그인 회원이면 본인 제외 */
    public function checkEmail(Request $request, MemberService $memberService)
    {
        $email = $request->input('email');
        if (!$email) {
            return response()->json(['available' => false, 'message' => '이메일을 입력해 주세요.'], 400);
        }
        $excludeId = $request->input('exclude_id') ?? Auth::guard('member')->id();
        $exists = $memberService->checkDuplicateEmail($email, $excludeId);
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? '이미 사용 중인 이메일입니다.' : '사용 가능한 이메일입니다.',
        ]);
    }

    /** 휴대폰 중복 확인 (AJAX) - 로그인 회원이면 본인 제외 */
    public function checkPhone(Request $request, MemberService $memberService)
    {
        $phone = $request->input('phone');
        if (!$phone) {
            return response()->json(['available' => false, 'message' => '휴대폰번호를 입력해 주세요.'], 400);
        }
        $excludeId = $request->input('exclude_id') ?? Auth::guard('member')->id();
        $exists = $memberService->checkDuplicatePhone($phone, $excludeId);
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

    /** 네이버 로그인 redirect */
    public function redirectToNaver()
    {
        return Socialite::driver('naver')->redirect();
    }

    /** 네이버 로그인 callback */
    public function handleNaverCallback(MemberAuthService $memberAuthService)
    {
        try {
            $providerUser = Socialite::driver('naver')->user();
        } catch (\Exception $e) {
            return redirect()->route('member.login')->withErrors(['social' => '네이버 로그인에 실패했습니다. 다시 시도해 주세요.']);
        }
        Log::debug('SNS raw data', ['provider' => 'naver', 'raw' => $providerUser->getRaw()]);

        $member = $memberAuthService->findMemberFromSocial('naver', $providerUser);
        if ($member) {
            Auth::guard('member')->login($member, false);
            request()->session()->regenerate();
            return redirect()->intended(route('mypage.application_status'));
        }

        $snsJoinData = $memberAuthService->getSnsJoinData('naver', $providerUser);
        session(['sns_join_data' => $snsJoinData]);
        return redirect()->route('member.join')->withInput($snsJoinData);
    }

    /** 카카오 로그인 redirect (이름·이메일·전화번호 동의 요청, 동의항목 ID: name, account_email, phone_number) */
    public function redirectToKakao()
    {
        return Socialite::driver('kakao')
            ->scopes(['name', 'account_email', 'phone_number'])
            ->redirect();
    }

    /** 카카오 로그인 callback */
    public function handleKakaoCallback(MemberAuthService $memberAuthService)
    {
        if (request()->has('error')) {
            $message = request('error_description', request('error', '사용자가 로그인을 취소했거나 카카오에서 오류가 반환되었습니다.'));
            return redirect()->route('member.login')->withErrors(['social' => $message]);
        }

        try {
            $providerUser = Socialite::driver('kakao')->user();
        } catch (\Exception $e) {
            Log::channel('single')->warning('카카오 로그인 콜백 실패', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('member.login')->withErrors(['social' => '카카오 로그인에 실패했습니다. 다시 시도해 주세요.']);
        }
        Log::debug('SNS raw data', ['provider' => 'kakao', 'raw' => $providerUser->getRaw()]);

        $member = $memberAuthService->findMemberFromSocial('kakao', $providerUser);
        if ($member) {
            Auth::guard('member')->login($member, false);
            request()->session()->regenerate();
            return redirect()->intended(route('mypage.application_status'));
        }

        $snsJoinData = $memberAuthService->getSnsJoinData('kakao', $providerUser);
        session(['sns_join_data' => $snsJoinData]);
        return redirect()->route('member.join')->withInput($snsJoinData);
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
        $snsJoinData = session('sns_join_data');
        return view('member.join', array_merge($this->memberMenuMeta('회원가입'), [
            'snsJoinData' => $snsJoinData,
        ]));
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
