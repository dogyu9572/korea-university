<?php

namespace App\Http\Controllers\Backoffice;

use App\Services\Backoffice\EducationApplicationService;
use App\Http\Requests\Backoffice\EducationApplicationStoreRequest;
use App\Http\Requests\Backoffice\EducationApplicationUpdateRequest;
use App\Models\EducationProgram;
use App\Models\EducationApplication;
use Illuminate\Http\Request;

class EducationApplicationController extends BaseController
{
    protected EducationApplicationService $educationApplicationService;

    public function __construct(EducationApplicationService $educationApplicationService)
    {
        $this->educationApplicationService = $educationApplicationService;
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
     * 교육 신청 등록 폼을 표시합니다.
     */
    public function create(Request $request)
    {
        $programId = $request->get('program');
        $program = null;
        
        if ($programId) {
            $program = EducationProgram::find($programId);
        }

        $programs = EducationProgram::whereIn('education_type', ['정기교육', '수시교육'])
            ->orderBy('name', 'asc')
            ->get();

        return $this->view('backoffice.education-applications.create', compact('program', 'programs'));
    }

    /**
     * 교육 신청을 저장합니다.
     */
    public function store(EducationApplicationStoreRequest $request)
    {
        try {
            $application = $this->educationApplicationService->createApplication($request);
            $programId = $application->education_program_id;

            return redirect()->route('backoffice.education-applications.show', $programId)
                ->with('success', '교육 신청이 등록되었습니다.');
        } catch (\Exception $e) {
            return redirect()->route('backoffice.education-applications.create', ['program' => $request->education_program_id])
                ->with('error', '등록 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * 교육 신청 수정 폼을 표시합니다.
     */
    public function edit(EducationApplication $education_application)
    {
        $application = $this->educationApplicationService->getApplication($education_application->id);
        return $this->view('backoffice.education-applications.edit', compact('application'));
    }

    /**
     * 교육 신청을 수정합니다.
     */
    public function update(EducationApplicationUpdateRequest $request, EducationApplication $education_application)
    {
        try {
            $application = $this->educationApplicationService->updateApplication($education_application, $request);
            $programId = $application->education_program_id;

            return redirect()->route('backoffice.education-applications.show', $programId)
                ->with('success', '교육 신청이 수정되었습니다.');
        } catch (\Exception $e) {
            return redirect()->route('backoffice.education-applications.edit', $education_application)
                ->with('error', '수정 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * 특정 교육 프로그램의 신청 명단을 표시합니다. (신청 인원 없어도 프로그램 정보 + 빈 명단으로 표시)
     */
    public function show($program, Request $request)
    {
        $program = EducationProgram::findOrFail($program);

        // 신청 인원이 없어도 빈 목록으로 조회 (404 발생하지 않음)
        $applications = $this->educationApplicationService->getApplicationList($program->id, $request);

        return $this->view('backoffice.education-applications.show', [
            'program' => $program,
            'applications' => $applications,
        ]);
    }

    /**
     * 교육 프로그램의 접수상태를 업데이트합니다.
     */
    public function updateStatus($program, Request $request)
    {
        $program = EducationProgram::findOrFail($program);
        
        $request->validate([
            'application_status' => 'required|in:접수중,접수마감,접수예정,비공개',
        ]);

        $program->update([
            'application_status' => $request->application_status,
        ]);

        return redirect()->route('backoffice.education-applications.show', $program->id)
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
        $program = EducationProgram::findOrFail($program);
        
        // 선택된 항목 ID 배열 (POST 요청일 때)
        $applicationIds = null;
        if ($request->isMethod('POST')) {
            $applicationIds = $request->input('application_ids', []);
            if (empty($applicationIds)) {
                $applicationIds = null; // 빈 배열이면 전체 다운로드
            }
        }
        
        $applications = $this->educationApplicationService->exportApplicationsToExcel($program->id, $request, $applicationIds);

        // CSV 형식으로 다운로드
        $filename = 'education_applications_' . $program->id . '_' . date('YmdHis') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // BOM 추가 (한글 깨짐 방지)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // 헤더
            fputcsv($file, [
                'No',
                '신청번호',
                '학교명',
                '신청자명',
                '신청자 ID',
                '휴대폰 번호',
                '이메일',
                '결제상태',
                '신청일시',
                '세금계산서 발행여부',
                '이수 여부',
                '이수증/수료증 번호',
                '영수증 번호',
                '참가비',
                '결제방법',
                '입금일시',
                '현금영수증',
                '현금영수증 용도',
                '현금영수증 발행번호',
                '세금계산서',
                '상호명',
                '사업자등록번호',
                '담당자명',
                '담당자 이메일',
                '담당자 휴대폰',
                '환불계좌 예금주',
                '환불계좌 은행',
                '환불계좌 번호',
                '설문조사 여부'
            ]);
            
            // 데이터
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
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * 신청 삭제
     */
    public function destroy(EducationApplication $education_application)
    {
        $programId = $education_application->education_program_id;
        $education_application->delete();

        return redirect()->route('backoffice.education-applications.show', $programId)
            ->with('success', '신청 내역이 삭제되었습니다.');
    }
}
