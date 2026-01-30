<?php

namespace App\Http\Controllers;

class MypageController extends Controller
{
    private function menuMeta(string $sNum, string $sName): array
    {
        return [
            'gNum' => '05',
            'sNum' => $sNum,
            'gName' => '마이페이지',
            'sName' => $sName,
        ];
    }

    public function application_status()
    {
        return view('mypage.application_status', $this->menuMeta('01', '교육 신청 현황'));
    }

    public function application_status_view()
    {
        return view('mypage.application_status_view', $this->menuMeta('01', '교육 신청 현황'));
    }

    public function application_status_view2()
    {
        return view('mypage.application_status_view2', $this->menuMeta('01', '교육 신청 현황'));
    }

    public function my_qualification()
    {
        return view('mypage.my_qualification', $this->menuMeta('02', '나의 자격 현황'));
    }

    public function my_qualification_view()
    {
        return view('mypage.my_qualification_view', $this->menuMeta('02', '나의 자격 현황'));
    }

    public function my_inquiries()
    {
        return view('mypage.my_inquiries', $this->menuMeta('03', '나의 문의'));
    }

    public function my_inquiries_view()
    {
        return view('mypage.my_inquiries_view', $this->menuMeta('03', '나의 문의'));
    }

    public function my_inquiries_write()
    {
        return view('mypage.my_inquiries_write', $this->menuMeta('03', '나의 문의'));
    }

    public function edit_member_information()
    {
        return view('mypage.edit_member_information', $this->menuMeta('04', '회원정보 수정'));
    }
}
