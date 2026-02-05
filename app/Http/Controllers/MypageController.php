<?php

namespace App\Http\Controllers;

use App\Http\Requests\MypageInquiryStoreRequest;
use App\Http\Requests\MypageMemberUpdateRequest;
use App\Http\Requests\MypageSecessionRequest;
use App\Services\Backoffice\InquiryService;
use App\Services\Backoffice\MemberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function my_inquiries(Request $request, InquiryService $inquiryService)
    {
        $memberId = Auth::guard('member')->id();
        $filters = ['category' => $request->get('category', '전체')];
        $inquiries = $inquiryService->getInquiriesByMember($memberId, $filters, 20);
        $categories = $inquiryService->getCategories();

        return view('mypage.my_inquiries', array_merge($this->menuMeta('03', '나의 문의'), compact('inquiries', 'categories', 'filters')));
    }

    public function my_inquiries_view(int $id, InquiryService $inquiryService)
    {
        $memberId = Auth::guard('member')->id();
        $inquiry = $inquiryService->getInquiryForMember($id, $memberId);
        $prevNext = $inquiryService->getPrevNextForMember($id, $memberId);

        return view('mypage.my_inquiries_view', array_merge($this->menuMeta('03', '나의 문의'), compact('inquiry', 'prevNext')));
    }

    public function my_inquiries_write(InquiryService $inquiryService)
    {
        $categories = $inquiryService->getCategories();
        return view('mypage.my_inquiries_write', array_merge($this->menuMeta('03', '나의 문의'), compact('categories')));
    }

    public function my_inquiries_store(MypageInquiryStoreRequest $request, InquiryService $inquiryService)
    {
        $inquiryService->createInquiry(Auth::guard('member')->id(), $request->validated(), $request->file('files'));
        return redirect()->route('mypage.my_inquiries')->with('success', '문의가 등록되었습니다.');
    }

    public function edit_member_information()
    {
        $member = Auth::guard('member')->user();
        return view('mypage.edit_member_information', array_merge($this->menuMeta('04', '회원정보 수정'), compact('member')));
    }

    public function update_member_information(MypageMemberUpdateRequest $request, MemberService $memberService)
    {
        $memberService->updateMember(Auth::guard('member')->id(), $request->getUpdateData());
        return redirect()->route('mypage.edit_member_information')->with('success', '회원정보가 수정되었습니다.');
    }

    public function secession(MypageSecessionRequest $request, MemberService $memberService)
    {
        $memberService->deleteMember(Auth::guard('member')->id());
        Auth::guard('member')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('member.login')->with('success', '회원 탈퇴가 완료되었습니다.');
    }
}
