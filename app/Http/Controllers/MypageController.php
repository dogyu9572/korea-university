<?php

namespace App\Http\Controllers;

use App\Http\Requests\MypageInquiryStoreRequest;
use App\Http\Requests\MypageMemberUpdateRequest;
use App\Http\Requests\MypageSecessionRequest;
use App\Models\EducationApplication;
use App\Services\ApplicationStatusService;
use App\Services\Backoffice\InquiryService;
use App\Services\Backoffice\MemberService;
use App\Services\QualificationStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function application_status(Request $request, ApplicationStatusService $applicationStatusService)
    {
        $memberId = Auth::guard('member')->id();
        $applications = $applicationStatusService->getListForMember($memberId, $request);
        $filters = $request->only(['name', 'education_type', 'period_year', 'period_month', 'status']);
        $educationTypeOptions = ['전체', '정기교육', '수시교육', '온라인교육', '세미나', '해외연수'];
        $statusOptions = ['전체', '신청완료', '수료', '미수료'];

        return view('mypage.application_status', array_merge(
            $this->menuMeta('01', '교육 신청 현황'),
            compact('applications', 'filters', 'educationTypeOptions', 'statusOptions')
        ));
    }

    public function application_status_cancel(Request $request, ApplicationStatusService $applicationStatusService)
    {
        $request->validate([
            'application_id' => 'required|integer|exists:education_applications,id',
        ]);

        $memberId = Auth::guard('member')->id();

        try {
            $applicationStatusService->cancelApplication((int) $request->application_id, $memberId);
            return redirect()->route('mypage.application_status')->with('success', '수강이 취소되었습니다.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('mypage.application_status')->with('error', $e->getMessage());
        }
    }

    public function application_status_view(int $id, ApplicationStatusService $applicationStatusService)
    {
        $memberId = Auth::guard('member')->id();
        try {
            $application = $applicationStatusService->getDetailForOfflineMember($id, $memberId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new NotFoundHttpException('해당 신청 내역을 찾을 수 없습니다.', $e);
        }
        return view('mypage.application_status_view', array_merge(
            $this->menuMeta('01', '교육 신청 현황'),
            ['application' => $application]
        ));
    }

    public function application_status_view2(int $id, ApplicationStatusService $applicationStatusService)
    {
        $memberId = Auth::guard('member')->id();
        try {
            $application = $applicationStatusService->getDetailForOnlineMember($id, $memberId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new NotFoundHttpException('해당 신청 내역을 찾을 수 없습니다.', $e);
        }
        return view('mypage.application_status_view2', array_merge(
            $this->menuMeta('01', '교육 신청 현황'),
            ['application' => $application]
        ));
    }

    private function printViewData(EducationApplication $application, string $sName): array
    {
        return [
            'gNum' => '99',
            'sNum' => '00',
            'gName' => '인쇄',
            'sName' => $sName,
            'application' => $application,
        ];
    }

    public function printReceipt(int $id, ApplicationStatusService $applicationStatusService, QualificationStatusService $qualificationStatusService)
    {
        $memberId = Auth::guard('member')->id();
        try {
            $application = $applicationStatusService->getDetailForOfflineMember($id, $memberId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            try {
                $application = $qualificationStatusService->getDetailForQualificationMember($id, $memberId);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e2) {
                throw new NotFoundHttpException('해당 신청 내역을 찾을 수 없습니다.', $e2);
            }
        }
        if ($application->payment_status !== '입금완료') {
            throw new NotFoundHttpException('영수증은 입금 완료 후 발급할 수 있습니다.');
        }
        return view('print.receipt', $this->printViewData($application, '영수증'));
    }

    public function printCertificateCompletion(int $id, ApplicationStatusService $applicationStatusService)
    {
        $memberId = Auth::guard('member')->id();
        try {
            $application = $applicationStatusService->getDetailForOfflineMember($id, $memberId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new NotFoundHttpException('해당 신청 내역을 찾을 수 없습니다.', $e);
        }
        if (!$application->is_completed || !$application->is_survey_completed) {
            throw new NotFoundHttpException('수료증은 이수 완료 및 설문 조사 완료 후 발급할 수 있습니다.');
        }
        return view('print.certificate_completion', $this->printViewData($application, '수료증'));
    }

    public function printCertificateFinish(int $id, ApplicationStatusService $applicationStatusService)
    {
        $memberId = Auth::guard('member')->id();
        try {
            $application = $applicationStatusService->getDetailForOfflineMember($id, $memberId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new NotFoundHttpException('해당 신청 내역을 찾을 수 없습니다.', $e);
        }
        if (!$application->is_completed || !$application->is_survey_completed) {
            throw new NotFoundHttpException('이수증은 이수 완료 및 설문 조사 완료 후 발급할 수 있습니다.');
        }
        return view('print.certificate_finish', $this->printViewData($application, '이수증'));
    }

    public function my_qualification(Request $request, QualificationStatusService $qualificationStatusService)
    {
        $memberId = Auth::guard('member')->id();
        $applications = $qualificationStatusService->getListForMember($memberId, $request);
        return view('mypage.my_qualification', array_merge(
            $this->menuMeta('02', '나의 자격 현황'),
            compact('applications')
        ));
    }

    public function my_qualification_view(int $id, QualificationStatusService $qualificationStatusService)
    {
        $memberId = Auth::guard('member')->id();
        try {
            $application = $qualificationStatusService->getDetailForQualificationMember($id, $memberId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new NotFoundHttpException('해당 신청 내역을 찾을 수 없습니다.', $e);
        }
        return view('mypage.my_qualification_view', array_merge(
            $this->menuMeta('02', '나의 자격 현황'),
            compact('application')
        ));
    }

    public function printAdmissionTicket(int $id, QualificationStatusService $qualificationStatusService)
    {
        $memberId = Auth::guard('member')->id();
        try {
            $application = $qualificationStatusService->getDetailForQualificationMember($id, $memberId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new NotFoundHttpException('해당 신청 내역을 찾을 수 없습니다.', $e);
        }
        return view('print.admission_ticket', $this->printViewData($application, '수험표'));
    }

    public function printCertificateQualification(int $id, QualificationStatusService $qualificationStatusService)
    {
        $memberId = Auth::guard('member')->id();
        try {
            $application = $qualificationStatusService->getDetailForQualificationMember($id, $memberId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new NotFoundHttpException('해당 신청 내역을 찾을 수 없습니다.', $e);
        }
        if (!$application->is_qualification_passed) {
            throw new NotFoundHttpException('합격 확인서는 합격 후 발급할 수 있습니다.');
        }
        return view('print.certificate_qualification', $this->printViewData($application, '합격확인서'));
    }

    public function printQualificationCertificate(int $id, QualificationStatusService $qualificationStatusService)
    {
        $memberId = Auth::guard('member')->id();
        try {
            $application = $qualificationStatusService->getDetailForQualificationMember($id, $memberId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new NotFoundHttpException('해당 신청 내역을 찾을 수 없습니다.', $e);
        }
        if (!$application->is_qualification_passed) {
            throw new NotFoundHttpException('자격증은 합격 후 발급할 수 있습니다.');
        }
        return view('print.certificate', $this->printViewData($application, '자격증'));
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
