<?php

namespace App\Http\Controllers\Backoffice;

use App\Services\Backoffice\EducationApplicationService;
use App\Services\Backoffice\CategoryService;
use App\Http\Requests\Backoffice\EducationApplicationStoreRequest;
use App\Http\Requests\Backoffice\EducationApplicationUpdateRequest;
use App\Models\Education;
use App\Models\OnlineEducation;
use App\Models\Certification;
use App\Models\SeminarTraining;
use App\Models\EducationApplication;
use Illuminate\Http\Request;

class EducationApplicationController extends BaseController
{
    protected EducationApplicationService $educationApplicationService;
    protected CategoryService $categoryService;

    public function __construct(EducationApplicationService $educationApplicationService, CategoryService $categoryService)
    {
        $this->educationApplicationService = $educationApplicationService;
        $this->categoryService = $categoryService;
    }

    /**
     * 교육 신청내역 목록을 표시합니다.
     */
    public function index(Request $request)
    {
        $programs = $this->educationApplicationService->getList($request);
        return $this->view('backoffice.education-applications.index', compact('programs'));
    }

    /**
     * 온라인 교육 신청내역 목록을 표시합니다.
     */
    public function onlineIndex(Request $request)
    {
        $programs = $this->educationApplicationService->getOnlineList($request);
        return $this->view('backoffice.education-applications.index', compact('programs'))
            ->with('isOnline', true);
    }

    /**
     * 자격증 신청내역 목록을 표시합니다.
     */
    public function certificationIndex(Request $request)
    {
        $programs = $this->educationApplicationService->getCertificationList($request);
        return $this->view('backoffice.certification-applications.index', compact('programs'));
    }

    /**
     * 세미나/해외연수 신청내역 목록을 표시합니다.
     */
    public function seminarTrainingIndex(Request $request)
    {
        $programs = $this->educationApplicationService->getSeminarTrainingList($request);
        return $this->view('backoffice.seminar-training-applications.index', compact('programs'));
    }

    /**
     * 교육 신청 등록 폼을 표시합니다.
     */
    public function create(Request $request)
    {
        $programId = $request->get('program');
        $program = $programId ? Education::find($programId) : null;
        $programs = Education::query()->orderBy('name', 'asc')->get();
        $examVenues = $this->categoryService->getExamVenues();

        return $this->view('backoffice.education-applications.create', compact('program', 'programs', 'examVenues'));
    }

    /**
     * 교육 신청을 저장합니다.
     * 온라인 교육 신청 저장 시 온라인 상세 페이지로, 그 외에는 일반 교육 상세로 리다이렉트합니다.
     */
    public function store(EducationApplicationStoreRequest $request)
    {
        $routeName = $request->route()->getName();
        $isOnline = $routeName === 'backoffice.online-education-applications.store';
        $isCertification = $routeName === 'backoffice.certification-applications.store';
        $isSeminarTraining = $routeName === 'backoffice.seminar-training-applications.store';

        try {
            $application = $this->educationApplicationService->createApplication($request);

            $indexRoute = $isSeminarTraining ? 'backoffice.seminar-training-applications.index'
                : ($isCertification ? 'backoffice.certification-applications.index'
                    : ($isOnline ? 'backoffice.online-education-applications.index' : 'backoffice.education-applications.index'));
            $createRoute = $isSeminarTraining ? 'backoffice.seminar-training-applications.create'
                : ($isCertification ? 'backoffice.certification-applications.create'
                    : ($isOnline ? 'backoffice.online-education-applications.create' : 'backoffice.education-applications.create'));

            return redirect()->route($indexRoute)
                ->with('success', '교육 신청이 등록되었습니다.');
        } catch (\Exception $e) {
            $createRoute = $isSeminarTraining ? 'backoffice.seminar-training-applications.create'
                : ($isCertification ? 'backoffice.certification-applications.create'
                    : ($isOnline ? 'backoffice.online-education-applications.create' : 'backoffice.education-applications.create'));
            $programParam = $request->education_id ?? $request->online_education_id ?? $request->certification_id ?? $request->seminar_training_id;
            return redirect()->route($createRoute, ['program' => $programParam])
                ->with('error', '등록 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * 교육 신청 수정 폼을 표시합니다.
     * 온라인 경로로 진입 시 온라인 전용 뷰를 사용합니다.
     */
    public function edit(EducationApplication $education_application)
    {
        $application = $this->educationApplicationService->getApplication($education_application->id);
        $routeName = request()->route()->getName();
        $isOnline = $routeName === 'backoffice.online-education-applications.edit';
        $isCertification = $routeName === 'backoffice.certification-applications.edit';
        $isSeminarTraining = $routeName === 'backoffice.seminar-training-applications.edit';

        if ($isCertification) {
            $examVenues = $this->categoryService->getExamVenues();
            return $this->view('backoffice.certification-applications.edit', compact('application', 'examVenues'));
        }
        if ($isSeminarTraining) {
            $programs = SeminarTraining::query()->orderBy('name', 'asc')->get();
            return $this->view('backoffice.seminar-training-applications.edit', compact('application', 'programs'));
        }
        if ($isOnline) {
            return $this->view('backoffice.online-education-applications.edit', compact('application'));
        }

        $examVenues = $this->categoryService->getExamVenues();
        return $this->view('backoffice.education-applications.edit', compact('application', 'examVenues'));
    }

    /**
     * 교육 신청을 수정합니다.
     */
    public function update(EducationApplicationUpdateRequest $request, EducationApplication $education_application)
    {
        $routeName = $request->route()->getName();
        $isCertification = $routeName === 'backoffice.certification-applications.update';
        $isOnline = $routeName === 'backoffice.online-education-applications.update';
        $isSeminarTraining = $routeName === 'backoffice.seminar-training-applications.update';
        $showRoute = $isSeminarTraining ? 'backoffice.seminar-training-applications.show'
            : ($isCertification ? 'backoffice.certification-applications.show'
                : ($isOnline ? 'backoffice.online-education-applications.show' : 'backoffice.education-applications.show'));
        $editRoute = $isSeminarTraining ? 'backoffice.seminar-training-applications.edit'
            : ($isCertification ? 'backoffice.certification-applications.edit'
                : ($isOnline ? 'backoffice.online-education-applications.edit' : 'backoffice.education-applications.edit'));

        try {
            $application = $this->educationApplicationService->updateApplication($education_application, $request);
            $programId = $application->program_id;

            return redirect()->route($showRoute, $programId)
                ->with('success', '교육 신청이 수정되었습니다.');
        } catch (\Exception $e) {
            return redirect()->route($editRoute, $education_application)
                ->with('error', '수정 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * 온라인 교육 신청 등록 폼을 표시합니다.
     */
    public function onlineCreate(Request $request)
    {
        $programId = $request->get('program');
        $program = $programId ? OnlineEducation::find($programId) : null;
        $programs = OnlineEducation::query()->orderBy('name', 'asc')->get();

        return $this->view('backoffice.online-education-applications.create', compact('program', 'programs'));
    }

    /**
     * 자격증 신청 등록 폼을 표시합니다.
     */
    public function certificationCreate(Request $request)
    {
        $programId = $request->get('program');
        $program = $programId ? Certification::find($programId) : null;
        $programs = Certification::query()->orderBy('name', 'asc')->get();
        $examVenues = $this->categoryService->getExamVenues();

        return $this->view('backoffice.certification-applications.create', compact('program', 'programs', 'examVenues'));
    }

    /**
     * 세미나/해외연수 신청 등록 폼을 표시합니다.
     */
    public function seminarTrainingCreate(Request $request)
    {
        $programId = $request->get('program');
        $program = $programId ? SeminarTraining::find($programId) : null;
        $programs = SeminarTraining::query()->orderBy('name', 'asc')->get();

        return $this->view('backoffice.seminar-training-applications.create', compact('program', 'programs'));
    }

    /**
     * 특정 교육의 신청 명단을 표시합니다.
     */
    public function show($program, Request $request)
    {
        $program = Education::findOrFail($program);
        $applications = $this->educationApplicationService->getApplicationList('education_id', $program->id, $request);

        return $this->view('backoffice.education-applications.show', [
            'program' => $program,
            'applications' => $applications,
        ]);
    }

    /**
     * 특정 온라인 교육의 신청 명단을 표시합니다.
     */
    public function onlineShow($program, Request $request)
    {
        $program = OnlineEducation::findOrFail($program);
        $applications = $this->educationApplicationService->getApplicationList('online_education_id', $program->id, $request);

        return $this->view('backoffice.online-education-applications.show', [
            'program' => $program,
            'applications' => $applications,
        ]);
    }

    /**
     * 특정 자격증의 신청 명단을 표시합니다.
     */
    public function certificationShow($program, Request $request)
    {
        $program = Certification::findOrFail($program);
        $applications = $this->educationApplicationService->getApplicationList('certification_id', $program->id, $request);

        return $this->view('backoffice.certification-applications.show', [
            'program' => $program,
            'applications' => $applications,
        ]);
    }

    /**
     * 특정 세미나/해외연수의 신청 명단을 표시합니다.
     */
    public function seminarTrainingShow($program, Request $request)
    {
        $program = SeminarTraining::findOrFail($program);
        $applications = $this->educationApplicationService->getApplicationList('seminar_training_id', $program->id, $request);

        return $this->view('backoffice.seminar-training-applications.show', [
            'program' => $program,
            'applications' => $applications,
        ]);
    }

    /**
     * 자격증 신청 명단의 성적을 일괄 저장합니다.
     */
    public function batchScores(Request $request, $program)
    {
        $program = Certification::findOrFail($program);

        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'nullable|integer|min:0',
        ]);

        $count = $this->educationApplicationService->batchUpdateScores($program->id, $request->scores);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $count . '건의 성적이 저장되었습니다.',
            ]);
        }

        return redirect()->route('backoffice.certification-applications.show', $program->id)
            ->with('success', $count . '건의 성적이 저장되었습니다.');
    }

    /**
     * 프로그램의 접수상태를 업데이트합니다.
     */
    public function updateStatus($program, Request $request)
    {
        $routeName = $request->route()->getName();
        if ($routeName === 'backoffice.seminar-training-applications.update-status') {
            $program = SeminarTraining::findOrFail($program);
        } elseif ($routeName === 'backoffice.certification-applications.update-status') {
            $program = Certification::findOrFail($program);
        } elseif ($routeName === 'backoffice.online-education-applications.update-status') {
            $program = OnlineEducation::findOrFail($program);
        } else {
            $program = Education::findOrFail($program);
        }

        $request->validate([
            'application_status' => 'required|in:접수중,접수마감,접수예정,비공개',
        ]);

        $program->update(['application_status' => $request->application_status]);

        $showRoute = $routeName === 'backoffice.seminar-training-applications.update-status'
            ? 'backoffice.seminar-training-applications.show'
            : ($routeName === 'backoffice.certification-applications.update-status'
                ? 'backoffice.certification-applications.show'
                : ($routeName === 'backoffice.online-education-applications.update-status'
                    ? 'backoffice.online-education-applications.show'
                    : 'backoffice.education-applications.show'));

        return redirect()->route($showRoute, $program->id)
            ->with('success', '접수상태가 업데이트되었습니다.');
    }

    /**
     * 일괄 입금완료 처리
     */
    public function batchPaymentComplete(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:education_applications,id',
        ]);

        $count = $this->educationApplicationService->batchPaymentComplete($request->application_ids);

        return response()->json([
            'success' => true,
            'message' => $count . '건이 입금완료로 변경되었습니다.',
        ]);
    }

    /**
     * 일괄 이수 처리
     */
    public function batchComplete(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:education_applications,id',
        ]);

        $count = $this->educationApplicationService->batchComplete($request->application_ids);

        return response()->json([
            'success' => true,
            'message' => $count . '건이 이수 처리되었습니다.',
        ]);
    }

    /**
     * 엑셀 다운로드 (선택된 항목만 또는 전체)
     */
    public function export($program, Request $request)
    {
        $routeName = $request->route()->getName();
        if (str_contains($routeName ?? '', 'certification-applications')) {
            $program = Certification::findOrFail($program);
            $programColumn = 'certification_id';
        } elseif (str_contains($routeName ?? '', 'seminar-training-applications')) {
            $program = SeminarTraining::findOrFail($program);
            $programColumn = 'seminar_training_id';
        } elseif (str_contains($routeName ?? '', 'online-education-applications')) {
            $program = OnlineEducation::findOrFail($program);
            $programColumn = 'online_education_id';
        } else {
            $program = Education::findOrFail($program);
            $programColumn = 'education_id';
        }

        $applicationIds = null;
        if ($request->isMethod('POST')) {
            $applicationIds = $request->input('application_ids', []);
            $applicationIds = empty($applicationIds) ? null : $applicationIds;
        }

        $applications = $this->educationApplicationService->exportApplicationsToExcel($programColumn, $program->id, $request, $applicationIds);
        $routeName = $request->route()->getName();
        $isCertification = $routeName === 'backoffice.certification-applications.export' || $routeName === 'backoffice.certification-applications.export.get';
        $isSeminarTraining = $routeName === 'backoffice.seminar-training-applications.export' || $routeName === 'backoffice.seminar-training-applications.export.get';

        $filename = $isCertification ? 'certification_applications_' . $program->id . '_' . date('YmdHis') . '.csv'
            : ($isSeminarTraining ? 'seminar_training_applications_' . $program->id . '_' . date('YmdHis') . '.csv'
                : 'education_applications_' . $program->id . '_' . date('YmdHis') . '.csv');
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($applications, $isCertification, $isSeminarTraining) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            if ($isCertification) {
                fputcsv($file, [
                    'No', '신청번호', '학교명', '신청자명', '신청자 ID', '휴대폰 번호', '결제상태', '신청일시', '세금계산서', '성적', '합격여부',
                    '수험표 번호', '합격확인서 번호', '자격확인서 번호', '영수증 번호', '응시료', '입금일시'
                ]);
                foreach ($applications as $index => $application) {
                    fputcsv($file, [
                        $index + 1,
                        $application->application_number ?? '',
                        $application->affiliation ?? '',
                        $application->applicant_name ?? '',
                        $application->member->login_id ?? '',
                        $application->phone_number ?? '',
                        $application->payment_status ?? '',
                        $application->application_date ? $application->application_date->format('Y-m-d H:i:s') : '',
                        $application->tax_invoice_status ?? '',
                        $application->score !== null ? $application->score : '',
                        $application->pass_status ?? '',
                        $application->exam_ticket_number ?? '',
                        $application->pass_confirmation_number ?? '',
                        $application->qualification_certificate_number ?? '',
                        $application->receipt_number ?? '',
                        $application->participation_fee ?? '',
                        $application->payment_date ? $application->payment_date->format('Y-m-d H:i:s') : '',
                    ]);
                }
            } elseif ($isSeminarTraining) {
                fputcsv($file, [
                    'No', '신청번호', '학교명', '신청자명', '신청자 ID', '휴대폰 번호', '룸메이트', '세금계산서', '결제상태', '신청일시', '이수 여부', '이수증/수료증 번호', '영수증 번호', '참가비', '입금일시'
                ]);
                foreach ($applications as $index => $application) {
                    $roommateDisplay = $application->roommate_name ?? '';
                    if ($application->roommate_phone ?? '') {
                        $roommateDisplay .= ($roommateDisplay ? ' / ' : '') . $application->roommate_phone;
                    }
                    fputcsv($file, [
                        $index + 1,
                        $application->application_number ?? '',
                        $application->affiliation ?? '',
                        $application->applicant_name ?? '',
                        $application->member->login_id ?? '',
                        $application->phone_number ?? '',
                        $roommateDisplay ?: '',
                        $application->tax_invoice_status ?? '',
                        $application->payment_status ?? '',
                        $application->application_date ? $application->application_date->format('Y-m-d H:i:s') : '',
                        $application->is_completed ? 'Y' : 'N',
                        $application->certificate_number ?? '',
                        $application->receipt_number ?? '',
                        $application->participation_fee ?? '',
                        $application->payment_date ? $application->payment_date->format('Y-m-d H:i:s') : '',
                    ]);
                }
            } else {
                fputcsv($file, [
                    'No', '신청번호', '학교명', '신청자명', '신청자 ID', '휴대폰 번호', '이메일', '결제상태', '신청일시', '세금계산서 발행여부', '이수 여부', '이수증/수료증 번호', '영수증 번호', '참가비', '결제방법', '입금일시', '현금영수증', '현금영수증 용도', '현금영수증 발행번호', '세금계산서', '상호명', '사업자등록번호', '담당자명', '담당자 이메일', '담당자 휴대폰', '환불계좌 예금주', '환불계좌 은행', '환불계좌 번호', '설문조사 여부'
                ]);
                foreach ($applications as $index => $application) {
                    fputcsv($file, [
                        $index + 1,
                        $application->application_number ?? '',
                        $application->affiliation ?? '',
                        $application->applicant_name ?? '',
                        $application->member->login_id ?? '',
                        $application->phone_number ?? '',
                        $application->email ?? '',
                        $application->payment_status ?? '',
                        $application->application_date ? $application->application_date->format('Y-m-d H:i:s') : '',
                        $application->tax_invoice_status ?? '',
                        $application->is_completed ? 'Y' : 'N',
                        $application->certificate_number ?? '',
                        $application->receipt_number ?? '',
                        $application->participation_fee ?? '',
                        is_array($application->payment_method) ? implode(', ', $application->payment_method) : ($application->payment_method ?? ''),
                        $application->payment_date ? $application->payment_date->format('Y-m-d H:i:s') : '',
                        $application->has_cash_receipt ? 'Y' : 'N',
                        $application->cash_receipt_purpose ?? '',
                        $application->cash_receipt_number ?? '',
                        $application->has_tax_invoice ? 'Y' : 'N',
                        $application->company_name ?? '',
                        $application->registration_number ?? '',
                        $application->contact_person_name ?? '',
                        $application->contact_person_email ?? '',
                        $application->contact_person_phone ?? '',
                        $application->refund_account_holder ?? '',
                        $application->refund_bank_name ?? '',
                        $application->refund_account_number ?? '',
                        $application->is_survey_completed ? 'Y' : 'N',
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * 신청 삭제
     */
    public function destroy(EducationApplication $education_application)
    {
        $programId = $education_application->program_id;
        $education_application->delete();

        $routeName = request()->route()->getName();
        $showRoute = $routeName === 'backoffice.seminar-training-applications.destroy'
            ? 'backoffice.seminar-training-applications.show'
            : ($routeName === 'backoffice.certification-applications.destroy'
                ? 'backoffice.certification-applications.show'
                : ($routeName === 'backoffice.online-education-applications.destroy'
                    ? 'backoffice.online-education-applications.show'
                    : 'backoffice.education-applications.show'));

        return redirect()->route($showRoute, $programId)
            ->with('success', '신청 내역이 삭제되었습니다.');
    }
}
