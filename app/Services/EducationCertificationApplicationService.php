<?php

namespace App\Services;

use App\Http\Requests\EducationCertification\CertificationApplicationRequest;
use App\Http\Requests\EducationCertification\EducationProgramApplicationRequest;
use App\Http\Requests\EducationCertification\OnlineEducationApplicationRequest;
use App\Models\Category;
use App\Models\Certification;
use App\Models\Education;
use App\Models\EducationApplication;
use App\Models\EducationApplicationAttachment;
use App\Models\Member;
use App\Models\OnlineEducation;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EducationCertificationApplicationService
{
    private const PER_PAGE = 9;

    /**
     * 교육·자격증·온라인 프로그램 목록을 조회합니다.
     */
    public function getList(Request $request): LengthAwarePaginator
    {
        $tab = $request->get('tab', 'all');
        $tab = in_array($tab, ['all', 'education', 'certification', 'online']) ? $tab : 'all';
        $sort = $request->get('sort', 'created_at');
        $sort = in_array($sort, ['created_at', 'application_start']) ? $sort : 'created_at';

        if ($tab === 'all') {
            return $this->getMergedList($request, $sort);
        }

        if ($tab === 'education') {
            return $this->getEducationList($request, $sort);
        }

        if ($tab === 'certification') {
            return $this->getCertificationList($request, $sort);
        }

        return $this->getOnlineEducationList($request, $sort);
    }

    /**
     * 교육 상세를 조회합니다. 비공개 시 null 반환.
     */
    public function getEducationDetail(int $id): ?Education
    {
        $item = Education::query()
            ->with(['attachments'])
            ->withCount(['applications as applications_count' => fn ($q) => $q->whereNull('cancelled_at')])
            ->find($id);

        if (!$item || !$item->is_public || $item->application_status === '비공개') {
            return null;
        }

        return $item;
    }

    /**
     * 자격증 상세를 조회합니다. 비공개 시 null 반환.
     */
    public function getCertificationDetail(int $id): ?Certification
    {
        $item = Certification::query()
            ->with(['attachments'])
            ->withCount(['applications as applications_count' => fn ($q) => $q->whereNull('cancelled_at')])
            ->find($id);

        if (!$item || !$item->is_public || $item->application_status === '비공개') {
            return null;
        }

        return $item;
    }

    /**
     * 온라인교육 상세를 조회합니다. 비공개 시 null 반환.
     */
    public function getOnlineEducationDetail(int $id): ?OnlineEducation
    {
        $item = OnlineEducation::query()
            ->with(['attachments', 'lectures'])
            ->withCount(['applications as applications_count' => fn ($q) => $q->whereNull('cancelled_at')])
            ->find($id);

        if (!$item || !$item->is_public || $item->application_status === '비공개') {
            return null;
        }

        return $item;
    }

    /**
     * 교육 상세 뷰용 표시 데이터를 준비합니다.
     */
    public function prepareEducationDetailView(Education $e): array
    {
        $enrolled = $e->applications_count ?? 0;
        $capacity = $e->capacity ?? 0;
        $capacityUnlimited = $e->capacity_unlimited ?? false;
        $hasRemain = $capacityUnlimited || $enrolled < $capacity;

        $appPeriod = format_period_ko($e->application_start, $e->application_end);
        $periodText = $e->period_start && $e->period_end
            ? format_period_ko($e->period_start, $e->period_end)
            : format_period_ko($e->period_start, null, $e->period_time);

        $btn = get_application_button_state($this->getEffectiveApplicationStatusForDisplay($e), 'education', $e->id);

        return [
            'type_class' => ($e->education_type ?? '') === '수시교육' ? 'c2' : 'c1',
            'education_type' => $e->education_type ?? '정기교육',
            'name' => $e->name,
            'app_period' => $appPeriod,
            'period_text' => $periodText,
            'education_class' => $e->education_class ?? '-',
            'accommodation_text' => ($e->is_accommodation ?? false) ? '합숙(숙박 선택 가능)' : '비합숙',
            'enrolled' => $enrolled,
            'capacity' => $capacity,
            'capacity_unlimited' => $capacityUnlimited,
            'capacity_text' => ($capacityUnlimited && (int) $capacity > 0) ? '무제한' : ((int) $capacity) . '명',
            'has_remain' => $hasRemain,
            'btn_class' => $btn['class'],
            'btn_text' => $btn['text'],
            'apply_url' => $btn['url'],
            'thumb' => $e->thumbnail_path ?: '/images/sample.jpg',
        ];
    }

    /**
     * 자격증 상세 뷰용 표시 데이터를 준비합니다.
     */
    public function prepareCertificationDetailView(Certification $c): array
    {
        $enrolled = $c->applications_count ?? 0;
        $capacity = $c->capacity ?? 0;
        $capacityUnlimited = $c->capacity_unlimited ?? false;
        $hasRemain = $capacityUnlimited || $enrolled < $capacity;

        $appPeriod = format_period_ko($c->application_start, $c->application_end);
        $examDate = format_date_ko($c->exam_date);

        $btn = get_application_button_state($this->getEffectiveApplicationStatusForDisplay($c), 'certification', $c->id);

        return [
            'name' => $c->name,
            'app_period' => $appPeriod,
            'exam_date' => $examDate,
            'eligibility' => strip_tags($c->eligibility ?? '') ?: '-',
            'exam_method' => $c->exam_method ?? '-',
            'enrolled' => $enrolled,
            'capacity' => $capacity,
            'capacity_unlimited' => $capacityUnlimited,
            'capacity_text' => ($capacityUnlimited && (int) $capacity > 0) ? '무제한' : ((int) $capacity) . '명',
            'has_remain' => $hasRemain,
            'btn_class' => $btn['class'],
            'btn_text' => $btn['text'],
            'apply_url' => $btn['url'],
            'thumb' => $c->thumbnail_path ?: '/images/sample.jpg',
        ];
    }

    /**
     * 온라인교육 상세 뷰용 표시 데이터를 준비합니다.
     */
    public function prepareOnlineEducationDetailView(OnlineEducation $o): array
    {
        $enrolled = $o->applications_count ?? 0;
        $capacity = $o->capacity ?? 0;
        $capacityUnlimited = $o->capacity_unlimited ?? false;
        $hasRemain = $capacityUnlimited || $enrolled < $capacity;

        $appPeriod = format_period_ko($o->application_start, $o->application_end);
        $periodText = format_period_ko($o->period_start, $o->period_end);

        $educationClass = $o->education_class ?? '';
        if (!empty($o->completion_hours)) {
            $educationClass .= ($educationClass ? '(' : '') . '인정시간: ' . $o->completion_hours . '시간' . ($educationClass ? ')' : '');
        }
        $educationClass = $educationClass ?: '-';

        $feeText = ($o->is_free ?? false) ? '무료' : ($o->fee ? number_format($o->fee) . '원' : '-');

        $btn = get_application_button_state($this->getEffectiveApplicationStatusForDisplay($o), 'online', $o->id);

        return [
            'name' => $o->name,
            'app_period' => $appPeriod,
            'period_text' => $periodText,
            'enrolled' => $enrolled,
            'capacity' => $capacity,
            'capacity_unlimited' => $capacityUnlimited,
            'capacity_text' => ($capacityUnlimited && (int) $capacity > 0) ? '무제한' : ((int) $capacity) . '명',
            'has_remain' => $hasRemain,
            'target' => $o->target ?? '-',
            'education_class' => $educationClass,
            'fee_text' => $feeText,
            'btn_class' => $btn['class'],
            'btn_text' => $btn['text'],
            'apply_url' => $btn['url'],
            'thumb' => $o->thumbnail_path ?: '/images/sample.jpg',
        ];
    }

    /**
     * 프론트 교육 신청을 저장합니다.
     */
    public function submitEducationApplication(EducationProgramApplicationRequest $request, Member $member): EducationApplication
    {
        return DB::transaction(function () use ($request, $member) {
            $program = $this->lockEducationForApplication((int) $request->input('education_id'));

            $this->assertProgramAvailability($program, 'education_id');
            $this->assertCapacityAvailable('education_id', $program->id, $program->capacity, $program->capacity_unlimited);
            $this->assertNotDuplicated('education_id', $program->id, $member->id, '이미 해당 교육을 신청하셨습니다.');

            $feeTypeInput = (string) $request->input('fee_type');
            $this->assertEducationFeeMatchesMemberSchool($feeTypeInput, $member);
            [$feeType, $participationFee] = $this->resolveEducationFee($program, $feeTypeInput);

            $billing = $this->extractBillingData($request);

            $data = array_merge(
                $this->baseApplicationData($member, $request),
                $billing,
                [
                    'education_id' => $program->id,
                    'participation_fee' => $participationFee,
                    'fee_type' => $feeType,
                    'payment_method' => $program->payment_methods ?? null,
                    'tax_invoice_status' => !empty($billing['has_tax_invoice']) ? '신청완료' : '미신청',
                ]
            );

            $application = EducationApplication::create($data);

            $this->storeBusinessRegistration($application, $request->file('business_registration'));
            $this->storeAdditionalAttachments($application, $request->file('attachments', []));

            return $application;
        });
    }

    /**
     * 교육 신청 수정 (마이페이지)
     */
    public function updateApplication(EducationApplication $application, Request $request): void
    {
        $program = Education::query()->find($application->education_id);
        if (!$program) {
            throw new NotFoundHttpException();
        }

        $feeTypeInput = (string) $request->input('fee_type');
        $this->assertEducationFeeMatchesMemberSchool($feeTypeInput, $application->member);
        [$feeType, $participationFee] = $this->resolveEducationFee($program, $feeTypeInput);
        $billing = $this->extractBillingData($request);

        $application->update(array_merge($billing, [
            'participation_fee' => $participationFee,
            'fee_type' => $feeType,
            'tax_invoice_status' => !empty($billing['has_tax_invoice']) ? '신청완료' : '미신청',
        ]));

        if ($request->filled('remove_business_registration')) {
            $application->attachments()->where('type', 'business_registration')->delete();
        }
        if ($request->hasFile('business_registration')) {
            $application->attachments()->where('type', 'business_registration')->delete();
            $this->storeBusinessRegistration($application, $request->file('business_registration'));
        }
    }

    /**
     * 회원의 학교 정보를 기반으로 회원교/비회원교 구분을 반환합니다.
     * - 'member': 회원교 소속
     * - 'guest': 비회원교 소속 또는 미등록 학교
     * - null: 회원 정보에 학교명이 없는 경우
     */
    public function getMemberSchoolType(Member $member): ?string
    {
        $schoolName = trim((string) $member->school_name);
        if ($schoolName === '') {
            return null;
        }

        $school = School::query()
            ->where('school_name', $schoolName)
            ->first();

        if (!$school) {
            // 학교가 회원교 목록에 없으면 비회원교로 취급
            return 'guest';
        }

        return $school->is_member_school ? 'member' : 'guest';
    }

    /**
     * 프론트 자격증 신청을 저장합니다.
     */
    public function submitCertificationApplication(CertificationApplicationRequest $request, Member $member): EducationApplication
    {
        return DB::transaction(function () use ($request, $member) {
            $program = $this->lockCertificationForApplication((int) $request->input('certification_id'));

            $this->assertProgramAvailability($program, 'certification_id');
            $this->assertCapacityAvailable('certification_id', $program->id, $program->capacity, $program->capacity_unlimited);
            $this->assertNotDuplicated('certification_id', $program->id, $member->id, '이미 해당 자격증을 신청하셨습니다.');
            $this->assertExamVenueSelectable($program, (int) $request->input('exam_venue_id'));

            if (!$program->exam_fee) {
                throw ValidationException::withMessages([
                    'certification_id' => '응시료가 설정되지 않은 자격증입니다. 관리자에게 문의해주세요.',
                ]);
            }

            $billing = $this->extractBillingData($request);

            $data = array_merge(
                $this->baseApplicationData($member, $request),
                $billing,
                [
                    'certification_id' => $program->id,
                    'participation_fee' => $program->exam_fee,
                    'fee_type' => 'certification_exam_fee',
                    'payment_method' => $program->payment_methods ?? null,
                    'exam_venue_id' => (int) $request->input('exam_venue_id'),
                    'birth_date' => Carbon::parse($request->input('birth_date'))->format('Y-m-d'),
                    'tax_invoice_status' => !empty($billing['has_tax_invoice']) ? '신청완료' : '미신청',
                ]
            );

            $application = EducationApplication::create($data);

            $this->storeIdPhoto($application, $request->file('id_photo'));
            $this->storeBusinessRegistration($application, $request->file('business_registration'));
            $this->storeAdditionalAttachments($application, $request->file('attachments', []));

            return $application;
        });
    }

    /**
     * 프론트 온라인 교육 신청을 저장합니다.
     */
    public function submitOnlineEducationApplication(OnlineEducationApplicationRequest $request, Member $member): EducationApplication
    {
        return DB::transaction(function () use ($request, $member) {
            $program = $this->lockOnlineEducationForApplication((int) $request->input('online_education_id'));

            $this->assertProgramAvailability($program, 'online_education_id');
            $this->assertCapacityAvailable('online_education_id', $program->id, $program->capacity, $program->capacity_unlimited);
            $this->assertNotDuplicated('online_education_id', $program->id, $member->id, '이미 해당 온라인교육을 신청하셨습니다.');

            $billing = $this->extractBillingData($request);

            $participationFee = $program->is_free ? 0 : ($program->fee ?? 0);

            $data = array_merge(
                $this->baseApplicationData($member, $request),
                $billing,
                [
                    'online_education_id' => $program->id,
                    'participation_fee' => $participationFee,
                    'fee_type' => $program->is_free ? null : ($request->input('fee_type') ?: 'default'),
                    'payment_method' => $program->payment_methods ?? null,
                    'payment_status' => $program->is_free ? '무료' : '미입금',
                    'course_status' => '접수',
                    // 회원이 세금계산서 발행(Y) 선택 시 발행여부를 신청완료로 저장
                    'tax_invoice_status' => !empty($billing['has_tax_invoice']) ? '신청완료' : '미신청',
                ]
            );

            $application = EducationApplication::create($data);

            $this->storeBusinessRegistration($application, $request->file('business_registration'));
            $this->storeAdditionalAttachments($application, $request->file('attachments', []));

            return $application;
        });
    }

    /**
     * 공통 신청 기본 데이터 생성
     */
    private function baseApplicationData(Member $member, Request $request): array
    {
        $now = Carbon::now();
        $name = trim((string) $request->input('applicant_name')) ?: $member->name;
        $affiliation = trim((string) $request->input('affiliation')) ?: $member->school_name;
        $phone = trim((string) $request->input('phone_number')) ?: $member->phone_number;
        $email = trim((string) $request->input('email')) ?: $member->email;

        return [
            'application_number' => $this->generateApplicationNumber($now),
            'member_id' => $member->id,
            'applicant_name' => $name,
            'affiliation' => $affiliation,
            'phone_number' => $phone,
            'email' => $email,
            'application_date' => $now,
            'is_completed' => false,
            'is_survey_completed' => false,
            'payment_status' => '미입금',
            'receipt_status' => '신청완료',
        ];
    }

    /**
     * 교육 프로그램을 조회합니다. (신청용)
     */
    public function getEducationProgram(int $id): Education
    {
        $program = Education::query()->find($id);
        if (!$program || !$program->is_public || $program->application_status === '비공개') {
            throw new NotFoundHttpException();
        }

        return $program;
    }

    /**
     * 자격증 프로그램을 조회합니다. (신청용)
     */
    public function getCertificationProgram(int $id): Certification
    {
        $program = Certification::query()->find($id);
        if (!$program || !$program->is_public || $program->application_status === '비공개') {
            throw new NotFoundHttpException();
        }

        return $program;
    }

    /**
     * 온라인교육 프로그램을 조회합니다. (신청용)
     */
    public function getOnlineEducationProgram(int $id): OnlineEducation
    {
        $program = OnlineEducation::query()->find($id);
        if (!$program || !$program->is_public || $program->application_status === '비공개') {
            throw new NotFoundHttpException();
        }

        return $program;
    }

    /**
     * 교육 신청 가능 여부 확인
     */
    public function ensureEducationCanApply(Education $program): void
    {
        $this->assertProgramAvailability($program, 'education_id');
    }

    /**
     * 자격증 신청 가능 여부 확인
     */
    public function ensureCertificationCanApply(Certification $program): void
    {
        $this->assertProgramAvailability($program, 'certification_id');
        if (!$program->exam_fee) {
            throw ValidationException::withMessages([
                'certification_id' => '응시료가 설정되지 않아 신청할 수 없습니다.',
            ]);
        }
    }

    /**
     * 온라인교육 신청 가능 여부 확인
     */
    public function ensureOnlineEducationCanApply(OnlineEducation $program): void
    {
        $this->assertProgramAvailability($program, 'online_education_id');
    }

    /**
     * 자격증 시험장 목록을 반환합니다.
     */
    public function getCertificationExamVenues(Certification $program): Collection
    {
        $ids = $program->venue_category_ids ?? [];
        if (empty($ids)) {
            return collect();
        }

        return Category::query()
            ->whereIn('id', $ids)
            ->active()
            ->orderBy('name')
            ->get();
    }

    /**
     * 환불/증빙 관련 입력값을 정리합니다.
     */
    private function extractBillingData(Request $request): array
    {
        $hasCashReceipt = $request->boolean('has_cash_receipt');
        $hasTaxInvoice = $request->boolean('has_tax_invoice');

        return [
            'refund_account_holder' => $request->input('refund_account_holder'),
            'refund_bank_name' => $request->input('refund_bank_name'),
            'refund_account_number' => $request->input('refund_account_number'),
            'has_cash_receipt' => $hasCashReceipt,
            'cash_receipt_purpose' => $hasCashReceipt ? $request->input('cash_receipt_purpose') : null,
            'cash_receipt_number' => $hasCashReceipt ? $request->input('cash_receipt_number') : null,
            'has_tax_invoice' => $hasTaxInvoice,
            'company_name' => $hasTaxInvoice ? $request->input('company_name') : null,
            'registration_number' => $hasTaxInvoice ? $request->input('registration_number') : null,
            'contact_person_name' => $hasTaxInvoice ? $request->input('contact_person_name') : null,
            'contact_person_email' => $hasTaxInvoice ? $request->input('contact_person_email') : null,
            'contact_person_phone' => $hasTaxInvoice ? $request->input('contact_person_phone') : null,
        ];
    }

    /**
     * 교육 신청 가능 상태를 확인합니다.
     */
    private function assertProgramAvailability($program, string $field): void
    {
        if (!$program || !$program->is_public || $program->application_status === '비공개') {
            throw ValidationException::withMessages([
                $field => '신청 가능한 프로그램이 아닙니다.',
            ]);
        }

        $now = Carbon::now();

        if ($program->application_status !== '접수중') {
            throw ValidationException::withMessages([
                $field => '현재는 신청 기간이 아닙니다.',
            ]);
        }

        if ($program->application_start && $now->lt($program->application_start)) {
            throw ValidationException::withMessages([
                $field => '신청 시작 전입니다.',
            ]);
        }

        if ($program->application_end && $now->gt($program->application_end)) {
            throw ValidationException::withMessages([
                $field => '신청 기간이 종료되었습니다.',
            ]);
        }
    }

    /**
     * 목록/상세 버튼 표시용. 퍼블 기준 3종: 신청하기(신청 가능) / 신청마감(기간 끝 or 정원 마감) / 개설예정(기간 전).
     */
    private function getEffectiveApplicationStatusForDisplay($program): string
    {
        $status = $program->application_status ?? '';
        if ($status !== '접수중') {
            return $status ?: '접수예정';
        }
        $now = Carbon::now();
        if ($program->application_start && $now->lt($program->application_start)) {
            return '접수예정';
        }
        if ($program->application_end && $now->gt($program->application_end)) {
            return '접수마감';
        }
        $capacity = (int) ($program->capacity ?? 0);
        $capacityUnlimited = (bool) ($program->capacity_unlimited ?? false);
        $enrolled = (int) ($program->applications_count ?? 0);
        if (!$capacityUnlimited && $capacity <= 0) {
            return '접수마감';
        }
        if (!$capacityUnlimited && $capacity > 0 && $enrolled >= $capacity) {
            return '접수마감';
        }
        return '접수중';
    }

    /**
     * 정원 확인
     */
    private function assertCapacityAvailable(string $column, int $programId, ?int $capacity, bool $unlimited): void
    {
        if ($unlimited) {
            return;
        }
        if ($capacity === null || $capacity <= 0) {
            throw ValidationException::withMessages([
                $column => '모집 정원이 마감되었습니다.',
            ]);
        }

        $count = EducationApplication::query()
            ->where($column, $programId)
            ->whereNull('cancelled_at')
            ->lockForUpdate()
            ->count();

        if ($count >= $capacity) {
            throw ValidationException::withMessages([
                $column => '모집 정원이 마감되었습니다.',
            ]);
        }
    }

    /**
     * 중복 신청 여부 확인
     */
    private function assertNotDuplicated(string $column, int $programId, int $memberId, string $message): void
    {
        $exists = EducationApplication::query()
            ->where($column, $programId)
            ->where('member_id', $memberId)
            ->whereNull('cancelled_at')
            ->lockForUpdate()
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                $column => $message,
            ]);
        }
    }

    /**
     * 교육 참가비 유형을 해석합니다.
     */
    private function resolveEducationFee(Education $program, string $feeType): array
    {
        $mapping = [
            'member_twin' => 'fee_member_twin',
            'member_single' => 'fee_member_single',
            'member_no_stay' => 'fee_member_no_stay',
            'guest_twin' => 'fee_guest_twin',
            'guest_single' => 'fee_guest_single',
            'guest_no_stay' => 'fee_guest_no_stay',
        ];

        if (!array_key_exists($feeType, $mapping)) {
            throw ValidationException::withMessages([
                'fee_type' => '올바르지 않은 참가비 유형입니다.',
            ]);
        }

        $column = $mapping[$feeType];
        $amount = $program->{$column};

        if ($amount === null) {
            throw ValidationException::withMessages([
                'fee_type' => '선택한 참가비 유형은 신청할 수 없습니다.',
            ]);
        }

        return [$feeType, $amount];
    }

    /**
     * 선택한 참가비 유형이 회원의 회원교/비회원교 구분과 일치하는지 확인합니다.
     */
    private function assertEducationFeeMatchesMemberSchool(string $feeType, Member $member): void
    {
        $memberType = $this->getMemberSchoolType($member);
        if ($memberType === null) {
            // 회원 정보에 학교명이 없으면 제한하지 않음
            return;
        }

        $isMemberFee = str_starts_with($feeType, 'member_');
        $isGuestFee = str_starts_with($feeType, 'guest_');

        // 교육 참가비 유형이 회원교/비회원교와 무관한 다른 유형인 경우 (자격증 등)에는 검사하지 않음
        if (!$isMemberFee && !$isGuestFee) {
            return;
        }

        if ($memberType === 'member' && $isGuestFee) {
            throw ValidationException::withMessages([
                'fee_type' => '회원교 소속은 회원교 참가비만 선택할 수 있습니다.',
            ]);
        }

        if ($memberType === 'guest' && $isMemberFee) {
            throw ValidationException::withMessages([
                'fee_type' => '비회원교 소속은 회원교 참가비를 선택할 수 없습니다.',
            ]);
        }
    }

    /**
     * 교육 데이터를 잠금하고 조회합니다.
     */
    private function lockEducationForApplication(int $id): ?Education
    {
        return Education::query()
            ->withCount(['applications as applications_count' => fn ($q) => $q->whereNull('cancelled_at')])
            ->whereKey($id)
            ->lockForUpdate()
            ->first();
    }

    /**
     * 자격증 데이터를 잠금하고 조회합니다.
     */
    private function lockCertificationForApplication(int $id): ?Certification
    {
        return Certification::query()
            ->withCount(['applications as applications_count' => fn ($q) => $q->whereNull('cancelled_at')])
            ->whereKey($id)
            ->lockForUpdate()
            ->first();
    }

    /**
     * 온라인교육 데이터를 잠금하고 조회합니다.
     */
    private function lockOnlineEducationForApplication(int $id): ?OnlineEducation
    {
        return OnlineEducation::query()
            ->withCount(['applications as applications_count' => fn ($q) => $q->whereNull('cancelled_at')])
            ->whereKey($id)
            ->lockForUpdate()
            ->first();
    }

    /**
     * 시험장 선택이 가능한지 확인합니다.
     */
    private function assertExamVenueSelectable(Certification $program, int $venueId): void
    {
        $allowed = collect($program->venue_category_ids ?? [])->contains($venueId);

        if (!$allowed) {
            throw ValidationException::withMessages([
                'exam_venue_id' => '선택할 수 없는 시험장입니다.',
            ]);
        }
    }

    /**
     * 신청번호를 생성합니다. (KUCRA-연도-일련번호)
     */
    private function generateApplicationNumber(Carbon $date): string
    {
        $year = $date->format('Y');

        $lastNumber = EducationApplication::query()
            ->whereYear('created_at', $year)
            ->whereNotNull('application_number')
            ->where('application_number', 'like', 'KUCRA-' . $year . '-%')
            ->orderByDesc('id')
            ->value('application_number');

        $sequence = 1;
        if ($lastNumber && preg_match('/KUCRA-\d{4}-(\d{4})/', $lastNumber, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return 'KUCRA-' . $year . '-' . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * 사업자등록증 첨부를 저장합니다.
     */
    private function storeBusinessRegistration(EducationApplication $application, $file): void
    {
        if (!$file) {
            return;
        }

        $this->storeAttachment($application, $file, 'business_registration', 0);
    }

    /**
     * 추가 첨부파일을 저장합니다.
     *
     * @param array<int, \Illuminate\Http\UploadedFile|null> $files
     */
    private function storeAdditionalAttachments(EducationApplication $application, array $files): void
    {
        $order = 1;
        foreach ($files as $file) {
            if ($file) {
                $this->storeAttachment($application, $file, 'attachment', $order++);
            }
        }
    }

    /**
     * 첨부파일 저장 공통 처리
     */
    private function storeAttachment(EducationApplication $application, $file, string $type, int $order): void
    {
        $path = $file->store('education_applications/' . $type, 'public');

        EducationApplicationAttachment::create([
            'education_application_id' => $application->id,
            'path' => Storage::url($path),
            'name' => $file->getClientOriginalName(),
            'type' => $type,
            'order' => $order,
        ]);
    }

    /**
     * 자격증 증명사진을 저장합니다.
     */
    private function storeIdPhoto(EducationApplication $application, $file): void
    {
        if (!$file) {
            return;
        }

        $path = $file->store('education_applications/id_photos', 'public');
        $application->update(['id_photo_path' => $path]);
    }

    /**
     * 교육 카드용 표시 데이터를 준비합니다.
     * @param array<int> $appliedEducationIds 현재 회원이 이미 신청한 education_id 목록
     */
    public function prepareEducationCardData(Education $e, array $appliedEducationIds = []): array
    {
        $enrolled = $e->applications_count ?? 0;
        $capacity = $e->capacity ?? 0;
        $capacityUnlimited = $e->capacity_unlimited ?? false;
        $periodText = $e->period_start && $e->period_end
            ? format_period_ko($e->period_start, $e->period_end)
            : format_period_ko($e->period_start, null, $e->period_time);

        $alreadyApplied = in_array($e->id, $appliedEducationIds, true);
        $btn = $alreadyApplied
            ? ['class' => 'btn btn_end', 'text' => '신청완료', 'url' => 'javascript:void(0);', 'already_applied' => true]
            : get_application_button_state($this->getEffectiveApplicationStatusForDisplay($e), 'education', $e->id);

        return [
            'type_class' => ($e->education_type ?? '') === '수시교육' ? 'c2' : 'c1',
            'education_type' => $e->education_type ?? '정기교육',
            'name' => $e->name ?? '',
            'app_period' => format_period_ko($e->application_start, $e->application_end),
            'period_text' => $periodText,
            'enrolled' => $enrolled,
            'capacity' => $capacity,
            'capacity_unlimited' => $capacityUnlimited,
            'capacity_text' => ($capacityUnlimited && (int) $capacity > 0) ? '무제한' : ((int) $capacity) . '명',
            'has_remain' => $capacityUnlimited || $enrolled < $capacity,
            'target' => $e->target ?? '-',
            'education_class' => $e->education_class ?? '-',
            'location' => $e->location ?? '-',
            'fee_text' => format_education_fee($e),
            'btn' => $btn,
            'view_url' => route('education_certification.application_ec_view', $e->id),
            'thumb' => $e->thumbnail_path ?: '/images/sample.jpg',
        ];
    }

    /**
     * 자격증 카드용 표시 데이터를 준비합니다.
     *
     * @param array<int> $appliedCertificationIds 현재 회원이 이미 신청한 certification_id 목록
     */
    public function prepareCertificationCardData(Certification $c, array $appliedCertificationIds = []): array
    {
        $enrolled = $c->applications_count ?? 0;
        $capacity = $c->capacity ?? 0;
        $capacityUnlimited = $c->capacity_unlimited ?? false;

        $alreadyApplied = in_array($c->id, $appliedCertificationIds, true);
        $btn = $alreadyApplied
            ? ['class' => 'btn btn_end', 'text' => '신청완료', 'url' => 'javascript:void(0);', 'already_applied' => true]
            : get_application_button_state($this->getEffectiveApplicationStatusForDisplay($c), 'certification', $c->id);

        return [
            'name' => $c->name ?? '',
            'eligibility' => Str::limit(strip_tags($c->eligibility ?? ''), 80) ?: '-',
            'app_period' => format_period_ko($c->application_start, $c->application_end),
            'exam_date' => format_date_ko($c->exam_date),
            'enrolled' => $enrolled,
            'capacity' => $capacity,
            'capacity_unlimited' => $capacityUnlimited,
            'capacity_text' => ($capacityUnlimited && (int) $capacity > 0) ? '무제한' : ((int) $capacity) . '명',
            'has_remain' => $capacityUnlimited || $enrolled < $capacity,
            'btn' => $btn,
            'view_url' => route('education_certification.application_ec_view_type2', $c->id),
            'thumb' => $c->thumbnail_path ?: '/images/sample.jpg',
        ];
    }

    /**
     * 온라인교육 카드용 표시 데이터를 준비합니다.
     * @param array<int> $appliedOnlineEducationIds 현재 회원이 이미 신청한 online_education_id 목록
     */
    public function prepareOnlineEducationCardData(OnlineEducation $o, array $appliedOnlineEducationIds = []): array
    {
        $enrolled = $o->applications_count ?? 0;
        $capacity = $o->capacity ?? 0;
        $capacityUnlimited = $o->capacity_unlimited ?? false;
        $educationClass = $o->education_class ?? '';
        if (!empty($o->completion_hours)) {
            $educationClass .= ($educationClass ? '(' : '') . '인정시간: ' . $o->completion_hours . '시간' . ($educationClass ? ')' : '');
        }
        $educationClass = $educationClass ?: '-';

        $alreadyApplied = in_array($o->id, $appliedOnlineEducationIds, true);
        $btn = $alreadyApplied
            ? ['class' => 'btn btn_end', 'text' => '신청완료', 'url' => 'javascript:void(0);', 'already_applied' => true]
            : get_application_button_state($this->getEffectiveApplicationStatusForDisplay($o), 'online', $o->id);

        return [
            'name' => $o->name ?? '',
            'app_period' => format_period_ko($o->application_start, $o->application_end),
            'period_text' => format_period_ko($o->period_start, $o->period_end),
            'education_class' => $educationClass,
            'target' => $o->target ?? '-',
            'enrolled' => $enrolled,
            'capacity' => $capacity,
            'capacity_unlimited' => $capacityUnlimited,
            'capacity_text' => ($capacityUnlimited && (int) $capacity > 0) ? '무제한' : ((int) $capacity) . '명',
            'has_remain' => $capacityUnlimited || $enrolled < $capacity,
            'btn' => $btn,
            'view_url' => route('education_certification.application_ec_view_online', $o->id),
            'thumb' => $o->thumbnail_path ?: '/images/sample.jpg',
        ];
    }

    /**
     * 기간구분 연도 옵션을 반환합니다.
     */
    public function getPeriodYears(): array
    {
        $year = (int) now()->format('Y');
        return [$year + 1, $year, $year - 1];
    }

    /**
     * 탭별 총 개수를 반환합니다.
     */
    public function getTabCounts(): array
    {
        return [
            'all' => $this->getEducationQuery(null)->count()
                + $this->getCertificationQuery(null)->count()
                + $this->getOnlineEducationQuery(null)->count(),
            'education' => $this->getEducationQuery(null)->count(),
            'certification' => $this->getCertificationQuery(null)->count(),
            'online' => $this->getOnlineEducationQuery(null)->count(),
        ];
    }

    private function getEducationQuery(?Request $request)
    {
        $query = Education::query()
            ->where('is_public', true)
            ->whereNot('application_status', '비공개')
            ->withCount(['applications as applications_count' => fn ($q) => $q->whereNull('cancelled_at')]);

        if ($request && $request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request && $request->filled('education_type')) {
            $query->where('education_type', $request->education_type);
        }
        if ($request && $request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }
        $this->applyDateFilter($query, $request, 'education');

        return $query;
    }

    private function getCertificationQuery(?Request $request)
    {
        $query = Certification::query()
            ->where('is_public', true)
            ->whereNot('application_status', '비공개')
            ->withCount(['applications as applications_count' => fn ($q) => $q->whereNull('cancelled_at')]);

        if ($request && $request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request && $request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }
        $this->applyDateFilter($query, $request, 'certification');

        return $query;
    }

    private function getOnlineEducationQuery(?Request $request)
    {
        $query = OnlineEducation::query()
            ->where('is_public', true)
            ->whereNot('application_status', '비공개')
            ->withCount(['applications as applications_count' => fn ($q) => $q->whereNull('cancelled_at')]);

        if ($request && $request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request && $request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }
        $this->applyDateFilter($query, $request, 'online');

        return $query;
    }

    private function applyDateFilter($query, ?Request $request, string $modelType): void
    {
        if (!$request || !$request->filled('period_year')) {
            return;
        }
        $dateType = $request->get('date_type', 'application');
        $year = (int) $request->period_year;
        $month = $request->filled('period_month') ? (int) $request->period_month : null;

        $dateColumn = match (true) {
            $modelType === 'certification' && $dateType === 'exam' => 'exam_date',
            $modelType === 'certification' && $dateType === 'education' => 'exam_date',
            $modelType === 'certification' => 'application_start',
            $dateType === 'education' => 'period_start',
            default => 'application_start',
        };

        $query->whereYear($dateColumn, $year);
        if ($month) {
            $query->whereMonth($dateColumn, $month);
        }
    }

    /**
     * 회원이 해당 교육을 이미 신청했는지 여부를 반환합니다.
     */
    public function hasMemberAppliedForEducation(int $memberId, int $educationId): bool
    {
        return EducationApplication::query()
            ->where('member_id', $memberId)
            ->where('education_id', $educationId)
            ->whereNull('cancelled_at')
            ->exists();
    }

    /**
     * 회원이 해당 온라인교육을 이미 신청했는지 여부를 반환합니다.
     */
    public function hasMemberAppliedForOnlineEducation(int $memberId, int $onlineEducationId): bool
    {
        return EducationApplication::query()
            ->where('member_id', $memberId)
            ->where('online_education_id', $onlineEducationId)
            ->whereNull('cancelled_at')
            ->exists();
    }

    /**
     * 회원이 이전 신청에서 등록한 현금영수증 발행 정보(가장 최근 1건)를 반환합니다.
     * 목록/폼 기본값 반영용.
     *
     * @return array{has_cash_receipt: bool, cash_receipt_purpose: ?string, cash_receipt_number: ?string}
     */
    public function getMemberLastCashReceiptPreferences(int $memberId): array
    {
        $app = EducationApplication::query()
            ->where('member_id', $memberId)
            ->where(function ($q) {
                $q->where('has_cash_receipt', true)
                    ->orWhereNotNull('cash_receipt_purpose')
                    ->orWhereNotNull('cash_receipt_number');
            })
            ->orderByDesc('created_at')
            ->first();

        if (!$app) {
            return ['has_cash_receipt' => false, 'cash_receipt_purpose' => null, 'cash_receipt_number' => null];
        }

        return [
            'has_cash_receipt' => (bool) $app->has_cash_receipt,
            'cash_receipt_purpose' => $app->cash_receipt_purpose,
            'cash_receipt_number' => $app->cash_receipt_number,
        ];
    }

    /**
     * 현재 로그인 회원이 이미 신청한 교육(education_id) ID 목록을 반환합니다.
     *
     * @return array<int>
     */
    private function getAppliedEducationIdsForCurrentMember(): array
    {
        $memberId = auth('member')?->id();
        if (!$memberId) {
            return [];
        }

        return EducationApplication::query()
            ->where('member_id', $memberId)
            ->whereNotNull('education_id')
            ->whereNull('cancelled_at')
            ->pluck('education_id')
            ->all();
    }

    /**
     * 현재 로그인 회원이 이미 신청한 자격증(certification_id) ID 목록을 반환합니다.
     *
     * @return array<int>
     */
    private function getAppliedCertificationIdsForCurrentMember(): array
    {
        $memberId = auth('member')?->id();
        if (!$memberId) {
            return [];
        }

        return EducationApplication::query()
            ->where('member_id', $memberId)
            ->whereNotNull('certification_id')
            ->whereNull('cancelled_at')
            ->pluck('certification_id')
            ->all();
    }

    /**
     * 현재 로그인 회원이 이미 신청한 온라인교육(online_education_id) ID 목록을 반환합니다.
     *
     * @return array<int>
     */
    private function getAppliedOnlineEducationIdsForCurrentMember(): array
    {
        $memberId = auth('member')?->id();
        if (!$memberId) {
            return [];
        }

        return EducationApplication::query()
            ->where('member_id', $memberId)
            ->whereNotNull('online_education_id')
            ->whereNull('cancelled_at')
            ->pluck('online_education_id')
            ->all();
    }

    private function getEducationList(Request $request, string $sort): LengthAwarePaginator
    {
        $query = $this->getEducationQuery($request);

        $query->orderBy($sort === 'application_start' ? 'application_start' : 'created_at', 'desc');

        $paginator = $query->paginate(self::PER_PAGE)->withQueryString();

        $appliedIds = $this->getAppliedEducationIdsForCurrentMember();

        $paginator->getCollection()->each(function ($item) use ($appliedIds) {
            $item->program_type = 'education';
            $item->card_data = $this->prepareEducationCardData($item, $appliedIds);
        });

        return $paginator;
    }

    private function getCertificationList(Request $request, string $sort): LengthAwarePaginator
    {
        $query = $this->getCertificationQuery($request);

        $query->orderBy($sort === 'application_start' ? 'application_start' : 'created_at', 'desc');

        $paginator = $query->paginate(self::PER_PAGE)->withQueryString();

        $appliedIds = $this->getAppliedCertificationIdsForCurrentMember();
        $paginator->getCollection()->each(function ($item) use ($appliedIds) {
            $item->program_type = 'certification';
            $item->card_data = $this->prepareCertificationCardData($item, $appliedIds);
        });

        return $paginator;
    }

    private function getOnlineEducationList(Request $request, string $sort): LengthAwarePaginator
    {
        $query = $this->getOnlineEducationQuery($request);

        $query->orderBy($sort === 'application_start' ? 'application_start' : 'created_at', 'desc');

        $paginator = $query->paginate(self::PER_PAGE)->withQueryString();

        $appliedOnlineIds = $this->getAppliedOnlineEducationIdsForCurrentMember();
        $paginator->getCollection()->each(function ($item) use ($appliedOnlineIds) {
            $item->program_type = 'online';
            $item->card_data = $this->prepareOnlineEducationCardData($item, $appliedOnlineIds);
        });

        return $paginator;
    }

    private function getMergedList(Request $request, string $sort): LengthAwarePaginator
    {
        $educations = $this->getEducationQuery($request)->get();
        $certifications = $this->getCertificationQuery($request)->get();
        $onlineEducations = $this->getOnlineEducationQuery($request)->get();

        $appliedEducationIds = $this->getAppliedEducationIdsForCurrentMember();

        $educations->each(function ($item) use ($appliedEducationIds) {
            $item->program_type = 'education';
            $item->card_data = $this->prepareEducationCardData($item, $appliedEducationIds);
        });
        $appliedCertificationIds = $this->getAppliedCertificationIdsForCurrentMember();
        $certifications->each(function ($item) use ($appliedCertificationIds) {
            $item->program_type = 'certification';
            $item->card_data = $this->prepareCertificationCardData($item, $appliedCertificationIds);
        });
        $appliedOnlineIds = $this->getAppliedOnlineEducationIdsForCurrentMember();
        $onlineEducations->each(function ($item) use ($appliedOnlineIds) {
            $item->program_type = 'online';
            $item->card_data = $this->prepareOnlineEducationCardData($item, $appliedOnlineIds);
        });

        $merged = $educations->concat($certifications)->concat($onlineEducations);

        $sortKey = $sort === 'application_start' ? 'application_start' : 'created_at';
        $merged = $merged->sortByDesc(function ($item) use ($sortKey) {
            $val = $item->{$sortKey} ?? null;
            return $val ? $val->format('Y-m-d H:i:s') : '0000-00-00';
        })->values();

        $total = $merged->count();
        $page = (int) request('page', 1);
        $page = max(1, $page);
        $items = $merged->slice(($page - 1) * self::PER_PAGE, self::PER_PAGE)->values();

        return new LengthAwarePaginator(
            $items,
            $total,
            self::PER_PAGE,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
