<?php

namespace App\Services\Backoffice;

use App\Models\EducationProgram;
use App\Models\EducationApplication;
use App\Models\EducationApplicationAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EducationApplicationService
{
    /**
     * 교육 신청내역 목록을 조회합니다.
     * 교육 프로그램별로 신청 인원 수를 집계하여 반환합니다.
     */
    public function getList(Request $request)
    {
        $query = EducationProgram::query()
            ->withCount('applications as enrolled_count');

        // 접수상태 검색
        if ($request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }

        // 교육유형 검색
        if ($request->filled('education_type')) {
            $query->where('education_type', $request->education_type);
        }

        // 교육기간 검색
        if ($request->filled('period_start')) {
            $query->where('period_start', '>=', $request->period_start);
        }
        if ($request->filled('period_end')) {
            $query->where('period_end', '<=', $request->period_end);
        }

        // 신청기간 검색
        if ($request->filled('application_start')) {
            $query->where('application_start', '>=', $request->application_start);
        }
        if ($request->filled('application_end')) {
            $query->where('application_end', '<=', $request->application_end);
        }

        // 교육명 검색
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // 정렬: 최신순
        $query->orderBy('created_at', 'desc');

        $perPage = $request->get('per_page', 20);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 특정 교육 프로그램의 신청 명단을 조회합니다.
     */
    public function getApplicationList(int $educationProgramId, Request $request)
    {
        $query = EducationApplication::query()
            ->where('education_program_id', $educationProgramId)
            ->with(['member', 'educationProgram']);

        // 결제상태 검색
        if ($request->filled('payment_status')) {
            if ($request->payment_status !== '전체') {
                $query->where('payment_status', $request->payment_status);
            }
        }

        // 신청자명/ID 검색
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', '%' . $search . '%')
                    ->orWhereHas('member', function ($memberQuery) use ($search) {
                        $memberQuery->where('login_id', 'like', '%' . $search . '%');
                    });
            });
        }

        // 정렬: 최신순
        $query->orderBy('application_date', 'desc');

        $perPage = $request->get('per_page', 20);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 엑셀 다운로드용 신청 명단 조회 (페이지네이션 없이 전체 조회)
     * @param int $educationProgramId 교육 프로그램 ID
     * @param Request $request 요청 객체
     * @param array|null $applicationIds 선택된 신청 ID 배열 (null이면 전체)
     */
    public function exportApplicationsToExcel(int $educationProgramId, Request $request, ?array $applicationIds = null)
    {
        $query = EducationApplication::query()
            ->where('education_program_id', $educationProgramId)
            ->with(['member', 'educationProgram']);

        // 선택된 항목만 필터링
        if ($applicationIds !== null && !empty($applicationIds)) {
            $query->whereIn('id', $applicationIds);
        }

        // 결제상태 검색
        if ($request->filled('payment_status')) {
            if ($request->payment_status !== '전체') {
                $query->where('payment_status', $request->payment_status);
            }
        }

        // 신청자명/ID 검색
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', '%' . $search . '%')
                    ->orWhereHas('member', function ($memberQuery) use ($search) {
                        $memberQuery->where('login_id', 'like', '%' . $search . '%');
                    });
            });
        }

        // 정렬: 최신순
        $query->orderBy('application_date', 'desc');

        return $query->get();
    }

    /**
     * 선택된 신청들의 결제상태를 일괄 입금완료로 변경합니다.
     */
    public function batchPaymentComplete(array $applicationIds): int
    {
        return EducationApplication::whereIn('id', $applicationIds)
            ->update([
                'payment_status' => '입금완료',
                'payment_date' => now(),
                'receipt_number' => $this->generateReceiptNumber(now()),
            ]);
    }

    /**
     * 선택된 신청들의 이수 여부를 일괄 Y로 변경합니다.
     */
    public function batchComplete(array $applicationIds): int
    {
        $updated = 0;
        foreach ($applicationIds as $id) {
            $application = EducationApplication::with('educationProgram')->find($id);
            if ($application && !$application->is_completed) {
                $application->update([
                    'is_completed' => true,
                    'certificate_number' => $this->generateCertificateNumber(now(), $application->educationProgram),
                ]);
                $updated++;
            }
        }
        return $updated;
    }

    /**
     * 영수증 번호 생성 (KUCRA-REC-연도-일련번호)
     */
    private function generateReceiptNumber(\Carbon\Carbon $date): string
    {
        $year = $date->format('Y');
        $lastNumber = EducationApplication::whereYear('created_at', $year)
            ->whereNotNull('receipt_number')
            ->where('receipt_number', 'like', 'KUCRA-REC-' . $year . '-%')
            ->orderBy('id', 'desc')
            ->value('receipt_number');
        
        $sequence = 1;
        if ($lastNumber && preg_match('/KUCRA-REC-\d{4}-(\d{4})/', $lastNumber, $matches)) {
            $sequence = (int)$matches[1] + 1;
        }
        
        return 'KUCRA-REC-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * 이수증/수료증 번호 생성
     * 이수증(CMP): KUCRA-CMP-연도-일련번호
     * 수료증(CRF): KUCRA-CRF-연도-일련번호
     */
    private function generateCertificateNumber(\Carbon\Carbon $date, ?EducationProgram $program = null): string
    {
        $year = $date->format('Y');
        
        // 프로그램의 certificate_type에 따라 구분 (이수증/수료증)
        // 기본값은 CMP (이수증)
        $type = 'CMP';
        if ($program && $program->certificate_type === '수료증') {
            $type = 'CRF';
        }
        
        $lastNumber = EducationApplication::whereYear('created_at', $year)
            ->whereNotNull('certificate_number')
            ->where('certificate_number', 'like', 'KUCRA-' . $type . '-' . $year . '-%')
            ->orderBy('id', 'desc')
            ->value('certificate_number');
        
        $sequence = 1;
        if ($lastNumber && preg_match('/KUCRA-' . $type . '-\d{4}-(\d{4})/', $lastNumber, $matches)) {
            $sequence = (int)$matches[1] + 1;
        }
        
        return 'KUCRA-' . $type . '-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * 신청번호 생성 (KUCRA-연도-일련번호)
     */
    private function generateApplicationNumber(\Carbon\Carbon $date): string
    {
        $year = $date->format('Y');
        $lastNumber = EducationApplication::whereYear('created_at', $year)
            ->whereNotNull('application_number')
            ->where('application_number', 'like', 'KUCRA-' . $year . '-%')
            ->orderBy('id', 'desc')
            ->value('application_number');
        
        $sequence = 1;
        if ($lastNumber && preg_match('/KUCRA-\d{4}-(\d{4})/', $lastNumber, $matches)) {
            $sequence = (int)$matches[1] + 1;
        }
        
        return 'KUCRA-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * 교육 신청을 생성합니다.
     */
    public function createApplication(Request $request): EducationApplication
    {
        DB::beginTransaction();
        try {
            $data = $request->only([
                'education_program_id',
                'member_id',
                'applicant_name',
                'affiliation',
                'phone_number',
                'email',
                'application_date',
                'is_completed',
                'is_survey_completed',
                'participation_fee',
                'fee_type',
                'payment_method',
                'payment_status',
                'payment_date',
                'tax_invoice_status',
                'has_cash_receipt',
                'cash_receipt_purpose',
                'cash_receipt_number',
                'has_tax_invoice',
                'company_name',
                'registration_number',
                'contact_person_name',
                'contact_person_email',
                'contact_person_phone',
                'refund_account_holder',
                'refund_bank_name',
                'refund_account_number',
            ]);

            // 신청번호 자동 생성
            $applicationDate = $request->application_date ? \Carbon\Carbon::parse($request->application_date) : now();
            $data['application_number'] = $this->generateApplicationNumber($applicationDate);

            // boolean 처리
            $data['is_completed'] = $request->has('is_completed') ? (bool)$request->is_completed : false;
            $data['is_survey_completed'] = $request->has('is_survey_completed') ? (bool)$request->is_survey_completed : false;
            $data['has_cash_receipt'] = $request->has('has_cash_receipt') ? (bool)$request->has_cash_receipt : false;
            $data['has_tax_invoice'] = $request->has('has_tax_invoice') ? (bool)$request->has_tax_invoice : false;

            // payment_method는 모델의 casts가 자동으로 JSON 처리
            if (!$request->has('payment_method') || !is_array($request->payment_method)) {
                $data['payment_method'] = null;
            }

            // 입금완료 시 영수증 번호 자동 생성
            if ($data['payment_status'] === '입금완료' && !$request->has('receipt_number')) {
                $data['receipt_number'] = $this->generateReceiptNumber(now());
                if (!$data['payment_date']) {
                    $data['payment_date'] = now();
                }
            }

            // 교육 프로그램 조회 (수료증 번호 생성 시 필요)
            $program = EducationProgram::find($data['education_program_id']);

            // 이수 완료 시 수료증 번호 자동 생성
            if ($data['is_completed'] && !$request->has('certificate_number')) {
                $data['certificate_number'] = $this->generateCertificateNumber(now(), $program);
            }

            // 교육 신청 생성
            $application = EducationApplication::create($data);

            // 첨부파일 저장
            if ($request->hasFile('attachments')) {
                $order = 0;
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('education_applications/attachments', 'public');
                    EducationApplicationAttachment::create([
                        'education_application_id' => $application->id,
                        'path' => Storage::url($path),
                        'name' => $file->getClientOriginalName(),
                        'type' => 'business_registration',
                        'order' => $order++,
                    ]);
                }
            }

            DB::commit();
            return $application;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 교육 신청을 수정합니다.
     */
    public function updateApplication(EducationApplication $application, Request $request): EducationApplication
    {
        DB::beginTransaction();
        try {
            $data = $request->only([
                'applicant_name',
                'affiliation',
                'phone_number',
                'email',
                'application_date',
                'is_completed',
                'is_survey_completed',
                'participation_fee',
                'fee_type',
                'payment_method',
                'payment_status',
                'payment_date',
                'tax_invoice_status',
                'has_cash_receipt',
                'cash_receipt_purpose',
                'cash_receipt_number',
                'has_tax_invoice',
                'company_name',
                'registration_number',
                'contact_person_name',
                'contact_person_email',
                'contact_person_phone',
                'refund_account_holder',
                'refund_bank_name',
                'refund_account_number',
            ]);

            // boolean 처리
            $data['is_completed'] = $request->has('is_completed') ? (bool)$request->is_completed : false;
            $data['is_survey_completed'] = $request->has('is_survey_completed') ? (bool)$request->is_survey_completed : false;
            $data['has_cash_receipt'] = $request->has('has_cash_receipt') ? (bool)$request->has_cash_receipt : false;
            $data['has_tax_invoice'] = $request->has('has_tax_invoice') ? (bool)$request->has_tax_invoice : false;

            // payment_method는 모델의 casts가 자동으로 JSON 처리
            if (!$request->has('payment_method') || !is_array($request->payment_method)) {
                $data['payment_method'] = null;
            }

            // 입금완료로 변경 시 영수증 번호 자동 생성
            if ($data['payment_status'] === '입금완료' && !$application->receipt_number) {
                $data['receipt_number'] = $this->generateReceiptNumber(now());
                if (!$data['payment_date']) {
                    $data['payment_date'] = now();
                }
            }

            // 이수 처리 시 수료증 번호 자동 생성
            if ($data['is_completed'] && !$application->certificate_number) {
                $program = $application->educationProgram;
                $data['certificate_number'] = $this->generateCertificateNumber(now(), $program);
            }

            // 교육 신청 수정
            $application->update($data);

            // 첨부파일 저장
            if ($request->hasFile('attachments')) {
                $lastOrder = $application->attachments()->max('order') ?? -1;
                $order = $lastOrder + 1;
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('education_applications/attachments', 'public');
                    EducationApplicationAttachment::create([
                        'education_application_id' => $application->id,
                        'path' => Storage::url($path),
                        'name' => $file->getClientOriginalName(),
                        'type' => 'business_registration',
                        'order' => $order++,
                    ]);
                }
            }

            // 첨부파일 삭제
            if ($request->has('delete_attachments') && is_array($request->delete_attachments)) {
                foreach ($request->delete_attachments as $attachmentId) {
                    $attachment = EducationApplicationAttachment::find($attachmentId);
                    if ($attachment && $attachment->education_application_id === $application->id) {
                        // 파일 삭제
                        $filePath = str_replace('/storage/', '', parse_url($attachment->path, PHP_URL_PATH));
                        Storage::disk('public')->delete($filePath);
                        $attachment->delete();
                    }
                }
            }

            DB::commit();
            return $application->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * 교육 신청 상세 정보를 조회합니다.
     */
    public function getApplication(int $id): EducationApplication
    {
        return EducationApplication::with(['educationProgram', 'member', 'attachments'])
            ->findOrFail($id);
    }
}
