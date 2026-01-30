<?php

namespace App\Http\Controllers;

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
