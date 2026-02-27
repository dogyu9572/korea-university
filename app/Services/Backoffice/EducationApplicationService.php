<?php

namespace App\Services\Backoffice;

use App\Models\Education;
use App\Models\OnlineEducation;
use App\Models\Certification;
use App\Models\SeminarTraining;
use App\Models\EducationApplication;
use App\Models\EducationApplicationAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EducationApplicationService
{
    /**
     * 교육 신청내역 목록을 조회합니다.
     * 교육별로 신청 인원 수를 집계하여 반환합니다.
     */
    public function getList(Request $request)
    {
        $query = Education::query()->withCount(['applications as enrolled_count' => fn ($q) => $q->whereNull('cancelled_at')]);
        return $this->buildListQuery($query, $request, 'education');
    }

    /**
     * 온라인 교육 신청내역 목록을 조회합니다.
     */
    public function getOnlineList(Request $request)
    {
        $query = OnlineEducation::query()->withCount(['applications as enrolled_count' => fn ($q) => $q->whereNull('cancelled_at')]);
        return $this->buildListQuery($query, $request, 'online_education');
    }

    /**
     * 세미나/해외연수 신청내역 목록을 조회합니다.
     */
    public function getSeminarTrainingList(Request $request)
    {
        $query = SeminarTraining::query()->withCount(['applications as enrolled_count' => fn ($q) => $q->whereNull('cancelled_at')]);
        return $this->buildListQuery($query, $request, 'seminar_training');
    }

    /**
     * 자격증 신청내역 목록을 조회합니다.
     */
    public function getCertificationList(Request $request)
    {
        $query = Certification::query()->withCount(['applications as enrolled_count' => fn ($q) => $q->whereNull('cancelled_at')]);

        if ($request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }
        if ($request->filled('exam_date_start')) {
            $query->where('exam_date', '>=', $request->exam_date_start);
        }
        if ($request->filled('exam_date_end')) {
            $query->where('exam_date', '<=', $request->exam_date_end);
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $query->orderBy('created_at', 'desc');

        $perPage = $request->get('per_page', 20);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 자격증 신청 명단의 성적을 일괄 저장합니다.
     *
     * @param  array<int, int|null>  $scores  [ application_id => score ]
     */
    public function batchUpdateScores(int $programId, array $scores): int
    {
        if (empty($scores)) {
            return 0;
        }

        $count = 0;
        $applications = EducationApplication::query()
            ->where('certification_id', $programId)
            ->whereIn('id', array_keys($scores))
            ->get();

        foreach ($applications as $application) {
            $score = $scores[$application->id] ?? null;
            if ($score !== null && $score !== '') {
                // 성적 저장
                $application->update(['score' => (int) $score]);
                $application->refresh();

                // 합격 여부(버튼 노출 기준)에 맞춰 자격확인서/합격확인서 번호 자동 생성
                if ($application->is_qualification_passed) {
                    $updateData = [];

                    if (!$application->qualification_certificate_number) {
                        $updateData['qualification_certificate_number'] = $this->generateQualificationCertificateNumber(now());
                    }
                    if (!$application->pass_confirmation_number) {
                        $updateData['pass_confirmation_number'] = $this->generatePassConfirmationNumber(now());
                    }
                    // pass_status가 아직 비어 있으면 '합격'으로 맞춰 둠 (기존 로직과 정합성 유지)
                    if (!$application->pass_status) {
                        $updateData['pass_status'] = '합격';
                    }

                    if (!empty($updateData)) {
                        $application->update($updateData);
                    }
                }

                $count++;
            }
        }

        return $count;
    }

    private function buildListQuery($query, Request $request, string $type): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if ($request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }

        if ($type === 'education' && $request->filled('education_type')) {
            $query->where('education_type', $request->education_type);
        }
        if ($type === 'seminar_training' && $request->filled('type')) {
            $query->where('type', $request->type);
        }

        if (in_array($type, ['education', 'online_education', 'seminar_training'])) {
            if ($request->filled('period_start')) {
                $query->where('period_start', '>=', $request->period_start);
            }
            if ($request->filled('period_end')) {
                $query->where('period_end', '<=', $request->period_end);
            }
        }
        if ($type === 'certification') {
            if ($request->filled('period_start')) {
                $query->where('exam_date', '>=', $request->period_start);
            }
            if ($request->filled('period_end')) {
                $query->where('exam_date', '<=', $request->period_end);
            }
        }

        if ($request->filled('application_start')) {
            $query->where('application_start', '>=', $request->application_start);
        }
        if ($request->filled('application_end')) {
            $query->where('application_end', '<=', $request->application_end);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $query->orderBy('created_at', 'desc');

        $perPage = $request->get('per_page', 20);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 특정 프로그램의 신청 명단을 조회합니다.
     */
    public function getApplicationList(string $programColumn, int $programId, Request $request)
    {
        $query = EducationApplication::query()
            ->where($programColumn, $programId)
            ->with(['member', 'education', 'onlineEducation', 'certification', 'seminarTraining']);

        if ($request->filled('payment_status') && $request->payment_status !== '전체') {
            if ($request->payment_status === '무료') {
                $query->where(function ($q) {
                    $q->where('payment_status', '무료')->orWhere('participation_fee', 0);
                });
            } else {
                $query->where('payment_status', $request->payment_status);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', '%' . $search . '%')
                    ->orWhereHas('member', function ($memberQuery) use ($search) {
                        $memberQuery->where('login_id', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($programColumn === 'seminar_training_id' && $request->filled('affiliation')) {
            $query->where('affiliation', 'like', '%' . $request->input('affiliation') . '%');
        }

        if ($programColumn === 'seminar_training_id' && $request->filled('accommodation')) {
            $acc = $request->input('accommodation');
            if ($acc === '2인1실') {
                $query->where('fee_type', 'like', '%twin%');
            } elseif ($acc === '1인실') {
                $query->where('fee_type', 'like', '%single%');
            } elseif ($acc === '비숙박') {
                $query->where('fee_type', 'like', '%no_stay%');
            }
        }

        if ($programColumn === 'seminar_training_id' && $request->filled('receipt_status')) {
            $query->where('receipt_status', $request->input('receipt_status'));
        }

        $query->orderBy('application_date', 'desc');

        return $query->paginate($request->get('per_page', 20))->withQueryString();
    }

    /**
     * 엑셀 다운로드용 신청 명단 조회
     */
    public function exportApplicationsToExcel(string $programColumn, int $programId, Request $request, ?array $applicationIds = null)
    {
        $query = EducationApplication::query()
            ->where($programColumn, $programId)
            ->with(['member', 'education', 'onlineEducation', 'certification', 'seminarTraining']);

        if ($applicationIds !== null && !empty($applicationIds)) {
            $query->whereIn('id', $applicationIds);
        }

        if ($request->filled('payment_status') && $request->payment_status !== '전체') {
            if ($request->payment_status === '무료') {
                $query->where(function ($q) {
                    $q->where('payment_status', '무료')->orWhere('participation_fee', 0);
                });
            } else {
                $query->where('payment_status', $request->payment_status);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', '%' . $search . '%')
                    ->orWhereHas('member', function ($memberQuery) use ($search) {
                        $memberQuery->where('login_id', 'like', '%' . $search . '%');
                    });
            });
        }

        return $query->orderBy('application_date', 'desc')->get();
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
            $application = EducationApplication::with(['education', 'onlineEducation', 'certification', 'seminarTraining'])->find($id);
            if ($application && !$application->is_completed) {
                $application->update([
                    'is_completed' => true,
                    'receipt_status' => '수료',
                    'certificate_number' => $this->generateCertificateNumber(now(), $application->program),
                ]);
                $updated++;
            }
        }
        return $updated;
    }

    /**
     * 신청별 결제상태 업데이트
     */
    public function updateApplicationPaymentStatus(EducationApplication $application, string $status): void
    {
        $data = [
            'payment_status' => $status,
            'payment_date' => $status === '입금완료' ? now() : null,
        ];
        if ($status === '입금완료' && !$application->receipt_number) {
            $data['receipt_number'] = $this->generateReceiptNumber(now());
        }
        $application->update($data);
    }

    /**
     * 신청별 이수 여부 업데이트 (접수상태 함께 갱신)
     */
    public function updateApplicationCompletionStatus(EducationApplication $application, bool $isCompleted): void
    {
        $program = $application->relationLoaded('education') || $application->relationLoaded('onlineEducation')
            || $application->relationLoaded('seminarTraining') ? $application->program
            : EducationApplication::with(['education', 'onlineEducation', 'certification', 'seminarTraining'])->find($application->id)?->program;

        $data = [
            'is_completed' => $isCompleted,
            'receipt_status' => $this->resolveReceiptStatus($isCompleted, $program),
        ];
        if ($isCompleted) {
            $data['completed_at'] = now();
            if (!$application->certificate_number) {
                $app = EducationApplication::with(['education', 'onlineEducation', 'certification', 'seminarTraining'])->find($application->id);
                $data['certificate_number'] = $this->generateCertificateNumber(now(), $app->program);
            }
        } else {
            $data['completed_at'] = null;
            $data['certificate_number'] = null;
        }
        $application->update($data);
    }

    /**
     * 이수 여부·교육기간에 따른 접수상태 결정 (수료/미수료/신청완료)
     */
    private function resolveReceiptStatus(bool $isCompleted, $program): string
    {
        if ($isCompleted) {
            return '수료';
        }
        if ($program && $program->period_end) {
            $endOfPeriod = $program->period_end instanceof \Carbon\Carbon
                ? $program->period_end->copy()->endOfDay()
                : \Carbon\Carbon::parse($program->period_end)->endOfDay();
            if ($endOfPeriod->isPast()) {
                return '미수료';
            }
        }
        return '신청완료';
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
    private function generateCertificateNumber(\Carbon\Carbon $date, $program = null): string
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
     * 자격확인서 번호 생성 (KUCRA-ELG-연도-일련번호)
     */
    private function generateQualificationCertificateNumber(\Carbon\Carbon $date): string
    {
        $year = $date->format('Y');
        $lastNumber = EducationApplication::whereYear('created_at', $year)
            ->whereNotNull('qualification_certificate_number')
            ->where('qualification_certificate_number', 'like', 'KUCRA-ELG-' . $year . '-%')
            ->orderBy('id', 'desc')
            ->value('qualification_certificate_number');
        
        $sequence = 1;
        if ($lastNumber && preg_match('/KUCRA-ELG-\d{4}-(\d{4})/', $lastNumber, $matches)) {
            $sequence = (int)$matches[1] + 1;
        }
        
        return 'KUCRA-ELG-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * 합격확인서 번호 생성 (KUCRA-PAS-연도-일련번호)
     */
    private function generatePassConfirmationNumber(\Carbon\Carbon $date): string
    {
        $year = $date->format('Y');
        $lastNumber = EducationApplication::whereYear('created_at', $year)
            ->whereNotNull('pass_confirmation_number')
            ->where('pass_confirmation_number', 'like', 'KUCRA-PAS-' . $year . '-%')
            ->orderBy('id', 'desc')
            ->value('pass_confirmation_number');
        
        $sequence = 1;
        if ($lastNumber && preg_match('/KUCRA-PAS-\d{4}-(\d{4})/', $lastNumber, $matches)) {
            $sequence = (int)$matches[1] + 1;
        }
        
        return 'KUCRA-PAS-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * 수험표 번호 생성 (KUCRA-EXM-연도-자격코드-회차-일련번호)
     * 자격코드와 회차는 프로그램 정보에서 가져옴
     */
    private function generateExamTicketNumber(\Carbon\Carbon $date, $program = null): string
    {
        $year = $date->format('Y');
        
        // 자격코드와 회차는 프로그램 정보에서 가져와야 함 (임시로 기본값 사용)
        $qualificationCode = '0000'; // TODO: 프로그램에서 자격코드 가져오기
        $round = '01'; // TODO: 프로그램에서 회차 가져오기
        
        $lastNumber = EducationApplication::whereYear('created_at', $year)
            ->whereNotNull('exam_ticket_number')
            ->where('exam_ticket_number', 'like', 'KUCRA-EXM-' . $year . '-' . $qualificationCode . '-' . $round . '-%')
            ->orderBy('id', 'desc')
            ->value('exam_ticket_number');
        
        $sequence = 1;
        if ($lastNumber && preg_match('/KUCRA-EXM-\d{4}-\d{4}-\d{2}-(\d{4})/', $lastNumber, $matches)) {
            $sequence = (int)$matches[1] + 1;
        }
        
        return 'KUCRA-EXM-' . $year . '-' . $qualificationCode . '-' . $round . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * 교육 신청을 생성합니다.
     */
    public function createApplication(Request $request): EducationApplication
    {
        DB::beginTransaction();
        try {
            $data = $request->only([
                'education_id',
                'online_education_id',
                'certification_id',
                'seminar_training_id',
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
                'cash_receipt_status',
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
                // 온라인교육 전용
                'course_status',
                'attendance_rate',
                // 자격증 전용
                'score',
                'pass_status',
                'exam_venue_id',
                'exam_ticket_number',
                'qualification_certificate_number',
                'pass_confirmation_number',
                'id_photo_path',
                'birth_date',
                // 세미나/해외연수 전용
                'roommate_member_id',
                'roommate_name',
                'roommate_phone',
                'receipt_status',
            ]);

            // 신청번호 자동 생성
            $applicationDate = $request->application_date ? \Carbon\Carbon::parse($request->application_date) : now();
            $data['application_number'] = $this->generateApplicationNumber($applicationDate);

            // boolean 처리 (input() 사용하여 폼값 정확히 반영)
            $data['is_completed'] = $request->boolean('is_completed');
            $data['is_survey_completed'] = $request->boolean('is_survey_completed');
            $data['has_cash_receipt'] = $request->boolean('has_cash_receipt');
            $data['has_tax_invoice'] = $request->boolean('has_tax_invoice');

            if (!isset($data['receipt_status']) || $data['receipt_status'] === '') {
                $data['receipt_status'] = $data['is_completed'] ? '수료' : '신청완료';
            }

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

            $program = $this->resolveProgramFromRequest($request);

            if ($program instanceof Education && $request->filled('member_type') && $request->filled('accommodation_type')) {
                $feeAmount = $this->resolveEducationParticipationFee($program, $request->member_type, $request->accommodation_type);
                if ($feeAmount !== null) {
                    $data['participation_fee'] = $feeAmount;
                    $data['fee_type'] = $request->member_type . ' ' . $request->accommodation_type;
                }
            }

            if ($data['is_completed']) {
                $data['completed_at'] = now();
            }

            if ($program) {
                if ($program instanceof \App\Models\OnlineEducation) {
                    if (!isset($data['course_status']) || empty($data['course_status'])) {
                        $data['course_status'] = '접수';
                    }
                }
                if ($program instanceof \App\Models\Certification) {
                    if (isset($data['pass_status']) && $data['pass_status'] === '합격') {
                        if (!isset($data['qualification_certificate_number']) || empty($data['qualification_certificate_number'])) {
                            $data['qualification_certificate_number'] = $this->generateQualificationCertificateNumber(now());
                        }
                        if (!isset($data['pass_confirmation_number']) || empty($data['pass_confirmation_number'])) {
                            $data['pass_confirmation_number'] = $this->generatePassConfirmationNumber(now());
                        }
                    }
                    if ($data['payment_status'] === '입금완료' && (!isset($data['exam_ticket_number']) || empty($data['exam_ticket_number']))) {
                        $data['exam_ticket_number'] = $this->generateExamTicketNumber(now(), $program);
                    }
                }
            }

            if ($data['is_completed'] && !$request->has('certificate_number')) {
                $data['certificate_number'] = $this->generateCertificateNumber(now(), $program);
            }

            // 교육 신청 생성
            $application = EducationApplication::create($data);

            // 자격증 증명사진 저장
            if ($request->hasFile('id_photo')) {
                $path = $request->file('id_photo')->store('education_applications/id_photos', 'public');
                $application->update(['id_photo_path' => $path]);
            }

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
                'gender',
                'application_date',
                'is_completed',
                'is_survey_completed',
                'participation_fee',
                'fee_type',
                'payment_method',
                'payment_status',
                'payment_date',
                'tax_invoice_status',
                'cash_receipt_status',
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
                // 온라인교육 전용
                'course_status',
                'attendance_rate',
                // 자격증 전용
                'score',
                'pass_status',
                'exam_venue_id',
                'exam_ticket_number',
                'qualification_certificate_number',
                'pass_confirmation_number',
                'id_photo_path',
                'birth_date',
                // 세미나/해외연수 전용
                'roommate_member_id',
                'roommate_name',
                'roommate_phone',
                'request_notes',
            ]);

            // boolean 처리 (input() 사용하여 폼값 정확히 반영)
            $data['is_completed'] = $request->boolean('is_completed');
            $data['is_survey_completed'] = $request->boolean('is_survey_completed');
            if ($request->filled('cash_receipt_status')) {
                $data['has_cash_receipt'] = in_array($request->input('cash_receipt_status'), ['신청완료', '발행완료'], true);
            } else {
                $data['has_cash_receipt'] = $request->boolean('has_cash_receipt');
            }
            $data['has_tax_invoice'] = $request->boolean('has_tax_invoice');

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

            $program = $application->program;

            if ($program instanceof Education && $request->filled('member_type') && $request->filled('accommodation_type')) {
                $feeAmount = $this->resolveEducationParticipationFee($program, $request->member_type, $request->accommodation_type);
                if ($feeAmount !== null) {
                    $data['participation_fee'] = $feeAmount;
                    $data['fee_type'] = $request->member_type . ' ' . $request->accommodation_type;
                }
            }

            if ($data['is_completed']) {
                if (!$application->completed_at) {
                    $data['completed_at'] = now();
                }
                if (!$application->certificate_number) {
                    $data['certificate_number'] = $this->generateCertificateNumber(now(), $program);
                }
            } else {
                $data['completed_at'] = null;
            }

            $validReceiptStatuses = ['접수취소', '신청완료', '수료', '미수료'];
            if ($request->filled('receipt_status') && in_array($request->receipt_status, $validReceiptStatuses, true)) {
                $data['receipt_status'] = $request->receipt_status;
                if ($data['receipt_status'] === '접수취소') {
                    $data['cancelled_at'] = $application->cancelled_at ?? now();
                } else {
                    $data['cancelled_at'] = null;
                }
            } else {
                $data['receipt_status'] = $this->resolveReceiptStatus($data['is_completed'], $program);
            }

            if ($program) {
                if ($program instanceof \App\Models\OnlineEducation) {
                    if (!isset($data['course_status']) || empty($data['course_status'])) {
                        $data['course_status'] = $application->course_status ?? '접수';
                    }
                }
                if ($program instanceof \App\Models\Certification) {
                    if (isset($data['pass_status']) && $data['pass_status'] === '합격' && $application->pass_status !== '합격') {
                        if (!isset($data['qualification_certificate_number']) || empty($data['qualification_certificate_number'])) {
                            $data['qualification_certificate_number'] = $this->generateQualificationCertificateNumber(now());
                        }
                        if (!isset($data['pass_confirmation_number']) || empty($data['pass_confirmation_number'])) {
                            $data['pass_confirmation_number'] = $this->generatePassConfirmationNumber(now());
                        }
                    }
                    if ($data['payment_status'] === '입금완료' && !$application->exam_ticket_number) {
                        $data['exam_ticket_number'] = $this->generateExamTicketNumber(now(), $program);
                    }
                }
            }

            // 자격증 증명사진 처리
            if ($request->hasFile('id_photo')) {
                if ($application->id_photo_path) {
                    $oldPath = str_starts_with($application->id_photo_path, '/') || str_contains($application->id_photo_path, '://')
                        ? preg_replace('#^.*/storage/#', '', parse_url($application->id_photo_path, PHP_URL_PATH) ?? '')
                        : $application->id_photo_path;
                    if ($oldPath !== '') {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
                $path = $request->file('id_photo')->store('education_applications/id_photos', 'public');
                $data['id_photo_path'] = $path;
            } elseif ($request->boolean('delete_id_photo') && $application->id_photo_path) {
                $oldPath = str_starts_with($application->id_photo_path, '/') || str_contains($application->id_photo_path, '://')
                    ? preg_replace('#^.*/storage/#', '', parse_url($application->id_photo_path, PHP_URL_PATH) ?? '')
                    : $application->id_photo_path;
                if ($oldPath !== '') {
                    Storage::disk('public')->delete($oldPath);
                }
                $data['id_photo_path'] = null;
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
        return EducationApplication::with(['education', 'onlineEducation', 'certification', 'seminarTraining', 'member', 'attachments', 'roommate', 'examVenue'])
            ->findOrFail($id);
    }

    /**
     * 자격증 신청(edit 화면 진입 시)에서 show 페이지 발급 버튼 노출 기준과 동일하게 번호를 자동 생성합니다.
     * - 수험표 번호: show에서 수험표 발급 버튼은 certification_id만 있으면 노출 → 번호 없으면 생성
     * - 자격확인서/합격확인서 번호: show에서 합격 시에만 버튼 노출 → 합격인데 번호 없으면 생성
     * - 영수증 번호: 입금완료 시에만 생성(기존 로직 유지)
     */
    public function ensureCertificationDocumentNumbers(EducationApplication $application): EducationApplication
    {
        if (!$application->certification_id) {
            return $application;
        }

        $updateData = [];

        // 수험표 번호: show와 동일 조건(certification_id 있으면 발급 버튼 노출) → 번호 없으면 생성
        if (empty($application->exam_ticket_number)) {
            $updateData['exam_ticket_number'] = $this->generateExamTicketNumber(now(), $application->program);
        }

        // 합격자용: 자격확인서/합격확인서 번호
        if ($application->is_qualification_passed) {
            if (empty($application->qualification_certificate_number)) {
                $updateData['qualification_certificate_number'] = $this->generateQualificationCertificateNumber(now());
            }
            if (empty($application->pass_confirmation_number)) {
                $updateData['pass_confirmation_number'] = $this->generatePassConfirmationNumber(now());
            }
            if (empty($application->pass_status)) {
                $updateData['pass_status'] = '합격';
            }
        }

        if (!empty($updateData)) {
            $application->update($updateData);
            $application->refresh();
        }

        return $application;
    }

    /**
     * 요청에서 프로그램 모델을 조회합니다.
     */
    private function resolveProgramFromRequest(Request $request)
    {
        if ($request->filled('education_id')) {
            return Education::find($request->education_id);
        }
        if ($request->filled('online_education_id')) {
            return OnlineEducation::find($request->online_education_id);
        }
        if ($request->filled('certification_id')) {
            return Certification::find($request->certification_id);
        }
        if ($request->filled('seminar_training_id')) {
            return SeminarTraining::find($request->seminar_training_id);
        }
        return null;
    }

    /**
     * 교육 프로그램에서 회원구분·숙박옵션에 따른 참가비 금액을 반환합니다.
     */
    private function resolveEducationParticipationFee(Education $program, string $memberType, string $accommodationType): ?float
    {
        $isGuest = ($memberType === '비회원교');
        $key = $isGuest ? 'guest' : 'member';
        if ($accommodationType === '2인1실') {
            $key .= '_twin';
        } elseif ($accommodationType === '1인실') {
            $key .= '_single';
        } else {
            $key .= '_no_stay';
        }
        $column = match ($key) {
            'member_twin' => 'fee_member_twin',
            'member_single' => 'fee_member_single',
            'member_no_stay' => 'fee_member_no_stay',
            'guest_twin' => 'fee_guest_twin',
            'guest_single' => 'fee_guest_single',
            'guest_no_stay' => 'fee_guest_no_stay',
            default => null,
        };
        if (!$column) {
            return null;
        }
        $value = $program->{$column} ?? null;
        return $value !== null ? (float) $value : null;
    }
}
