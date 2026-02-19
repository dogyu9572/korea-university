<?php

namespace App\Services;

use App\Models\EducationApplication;
use App\Models\EducationApplicationAttachment;
use App\Models\Member;
use App\Models\SeminarTraining;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Http\Kernel\Exception\NotFoundHttpException;

class SeminarTrainingApplicationService
{
    private const PER_PAGE = 9;

    public function getList(Request $request): LengthAwarePaginator
    {
        $tab = $request->get('tab', 'all');
        $tab = in_array($tab, ['all', 'seminar', 'overseas']) ? $tab : 'all';
        $sort = $request->get('sort', 'created_at');
        $sort = in_array($sort, ['created_at', 'application_start']) ? $sort : 'created_at';

        $query = $this->getSeminarTrainingQuery($request);

        if ($tab === 'seminar') {
            $query->where('type', '세미나');
        } elseif ($tab === 'overseas') {
            $query->where('type', '해외연수');
        }

        $query->orderBy($sort === 'application_start' ? 'application_start' : 'created_at', 'desc');

        $paginator = $query->paginate(self::PER_PAGE)->withQueryString();

        $appliedIds = $this->getAppliedSeminarTrainingIdsForCurrentMember();
        $paginator->getCollection()->each(function ($item) use ($appliedIds) {
            $item->program_type = 'seminar_training';
            $item->card_data = $this->prepareSeminarTrainingCardData($item, $appliedIds);
        });

        return $paginator;
    }

    public function getTabCounts(): array
    {
        $baseQuery = SeminarTraining::query()
            ->where('is_public', true)
            ->whereNot('application_status', '비공개');

        return [
            'all' => (clone $baseQuery)->count(),
            'seminar' => (clone $baseQuery)->where('type', '세미나')->count(),
            'overseas' => (clone $baseQuery)->where('type', '해외연수')->count(),
        ];
    }

    public function getPeriodYears(): array
    {
        $year = (int) now()->format('Y');
        return [$year + 1, $year, $year - 1];
    }

    public function getSeminarTrainingDetail(int $id): ?SeminarTraining
    {
        $item = SeminarTraining::query()
            ->with(['attachments'])
            ->withCount('applications as applications_count')
            ->find($id);

        if (!$item || !$item->is_public || $item->application_status === '비공개') {
            return null;
        }

        return $item;
    }

    public function getSeminarTrainingProgram(int $id): SeminarTraining
    {
        $program = SeminarTraining::query()->find($id);
        if (!$program || !$program->is_public || $program->application_status === '비공개') {
            throw new NotFoundHttpException();
        }

        return $program;
    }

    public function ensureSeminarTrainingCanApply(SeminarTraining $program): void
    {
        $this->assertProgramAvailability($program, 'seminar_training_id');
    }

    /**
     * 목록 카드용 표시 데이터. 이미 신청한 프로그램은 신청완료 버튼으로 표시합니다.
     *
     * @param array<int> $appliedSeminarTrainingIds 현재 회원이 이미 신청한 seminar_training_id 목록
     */
    public function prepareSeminarTrainingCardData(SeminarTraining $st, array $appliedSeminarTrainingIds = []): array
    {
        $enrolled = $st->applications_count ?? 0;
        $capacity = $st->capacity ?? 0;
        $capacityUnlimited = $st->capacity_unlimited ?? false;
        $periodText = $st->period_start && $st->period_end
            ? format_period_ko($st->period_start, $st->period_end)
            : format_period_ko($st->period_start, null, $st->period_time);

        $alreadyApplied = in_array($st->id, $appliedSeminarTrainingIds, true);
        $btn = $alreadyApplied
            ? ['class' => 'btn btn_end', 'text' => '신청완료', 'url' => 'javascript:void(0);', 'already_applied' => true]
            : get_application_button_state($this->getEffectiveApplicationStatusForDisplay($st), 'seminar_training', $st->id);

        return [
            'type_class' => ($st->type ?? '') === '해외연수' ? 'c6' : 'c5',
            'name' => $st->name ?? '',
            'app_period' => format_period_ko($st->application_start, $st->application_end),
            'period_text' => $periodText,
            'enrolled' => $enrolled,
            'capacity' => $capacity,
            'capacity_unlimited' => $capacityUnlimited,
            'capacity_text' => $capacityUnlimited ? '무제한' : $capacity . '명',
            'has_remain' => $capacityUnlimited || $enrolled < $capacity,
            'target' => $st->target ?? '-',
            'education_class' => $st->education_class ?? '-',
            'location' => $st->location ?? '-',
            'fee_text' => format_fee_with_option_names($st),
            'btn' => $btn,
            'view_url' => route('seminars_training.application_st_view', $st->id),
            'thumb' => $st->thumbnail_path ?: '/images/sample.jpg',
        ];
    }

    /**
     * 상세 페이지용 표시 데이터. 이미 신청한 경우 신청완료 버튼으로 표시합니다.
     */
    public function prepareSeminarTrainingDetailView(SeminarTraining $st, bool $alreadyApplied = false): array
    {
        $enrolled = $st->applications_count ?? 0;
        $capacity = $st->capacity ?? 0;
        $capacityUnlimited = $st->capacity_unlimited ?? false;
        $periodText = $st->period_start && $st->period_end
            ? format_period_ko($st->period_start, $st->period_end)
            : format_period_ko($st->period_start, null, $st->period_time);

        $btn = $alreadyApplied
            ? ['class' => 'btn btn_end', 'text' => '신청완료', 'url' => 'javascript:void(0);']
            : get_application_button_state($this->getEffectiveApplicationStatusForDisplay($st), 'seminar_training', $st->id);

        return [
            'type_class' => ($st->type ?? '') === '해외연수' ? 'c6' : 'c5',
            'type' => $st->type ?? '세미나',
            'name' => $st->name ?? '',
            'app_period' => format_period_ko($st->application_start, $st->application_end),
            'period_text' => $periodText,
            'total_sessions_class' => $st->total_sessions_class ?? '-',
            'education_class' => $st->education_class ?? '-',
            'accommodation_text' => ($st->is_accommodation ?? false) ? '합숙(숙박 선택 가능)' : '비합숙',
            'enrolled' => $enrolled,
            'capacity' => $capacity,
            'capacity_unlimited' => $capacityUnlimited,
            'capacity_text' => $capacityUnlimited ? '무제한' : $capacity . '명',
            'has_remain' => $capacityUnlimited || $enrolled < $capacity,
            'location' => $st->location ?? '-',
            'target' => $st->target ?? '-',
            'fee_text' => format_fee_with_option_names($st),
            'btn_class' => $btn['class'],
            'btn_text' => $btn['text'],
            'apply_url' => $btn['url'],
            'already_applied' => $alreadyApplied,
            'thumb' => $st->thumbnail_path ?: '/images/sample.jpg',
        ];
    }

    /**
     * 현재 로그인 회원이 이미 신청한 세미나·해외연수(seminar_training_id) ID 목록을 반환합니다.
     *
     * @return array<int>
     */
    public function getAppliedSeminarTrainingIdsForCurrentMember(): array
    {
        $memberId = auth('member')?->id();
        if (!$memberId) {
            return [];
        }

        return EducationApplication::query()
            ->where('member_id', $memberId)
            ->whereNotNull('seminar_training_id')
            ->whereNull('cancelled_at')
            ->pluck('seminar_training_id')
            ->all();
    }

    /**
     * 해당 회원이 이미 해당 세미나·해외연수를 신청했는지 여부를 반환합니다.
     */
    public function hasMemberAppliedForSeminarTraining(int $memberId, int $seminarTrainingId): bool
    {
        return EducationApplication::query()
            ->where('member_id', $memberId)
            ->where('seminar_training_id', $seminarTrainingId)
            ->whereNull('cancelled_at')
            ->exists();
    }

    public function submitSeminarTrainingApplication(Request $request, Member $member): EducationApplication
    {
        return DB::transaction(function () use ($request, $member) {
            $program = $this->lockSeminarTrainingForApplication((int) $request->input('seminar_training_id'));
            if (!$program) {
                throw new NotFoundHttpException();
            }

            $this->assertProgramAvailability($program, 'seminar_training_id');
            $this->assertCapacityAvailable('seminar_training_id', $program->id, $program->capacity, $program->capacity_unlimited);
            $this->assertNotDuplicated('seminar_training_id', $program->id, $member->id, '이미 해당 프로그램을 신청하셨습니다.');

            [$feeType, $participationFee] = $this->resolveSeminarTrainingFee($program, (string) $request->input('fee_type'));

            $billing = $this->extractBillingData($request);

            $data = array_merge(
                $this->baseApplicationData($member, $request),
                $billing,
                [
                    'seminar_training_id' => $program->id,
                    'participation_fee' => $participationFee,
                    'fee_type' => $feeType,
                    'payment_method' => $program->payment_methods ?? null,
                    'roommate_member_id' => $request->input('roommate_member_id') ? (int) $request->input('roommate_member_id') : null,
                    'roommate_name' => $request->filled('roommate_name') ? trim((string) $request->input('roommate_name')) : null,
                    'roommate_phone' => $request->filled('roommate_phone') ? trim((string) $request->input('roommate_phone')) : null,
                    'request_notes' => $request->filled('request_notes') ? trim((string) $request->input('request_notes')) : null,
                ]
            );

            $application = EducationApplication::create($data);

            $this->storeBusinessRegistration($application, $request->file('business_registration'));
            $this->storeAdditionalAttachments($application, $request->file('attachments', []));

            return $application;
        });
    }

    /**
     * 참가비 옵션 데이터 (교육과 동일 구조)
     *
     * @return array<int, array<string, mixed>>
     */
    public function buildSeminarTrainingFeeOptions(SeminarTraining $program): array
    {
        $groups = [
            [
                'label' => '회원교(1인당)',
                'items' => [
                    ['key' => 'member_twin', 'label' => '2인 1실', 'amount' => $program->fee_member_twin],
                    ['key' => 'member_single', 'label' => '1인실', 'amount' => $program->fee_member_single],
                    ['key' => 'member_no_stay', 'label' => '비숙박', 'amount' => $program->fee_member_no_stay],
                ],
            ],
            [
                'label' => '비회원교(1인당)',
                'items' => [
                    ['key' => 'guest_twin', 'label' => '2인 1실', 'amount' => $program->fee_guest_twin],
                    ['key' => 'guest_single', 'label' => '1인실', 'amount' => $program->fee_guest_single],
                    ['key' => 'guest_no_stay', 'label' => '비숙박', 'amount' => $program->fee_guest_no_stay],
                ],
            ],
        ];

        return collect($groups)
            ->map(function (array $group) {
                $items = collect($group['items'])
                    ->filter(fn ($item) => $item['amount'] !== null)
                    ->map(function ($item) {
                        $item['display_amount'] = number_format((float) $item['amount']);
                        return $item;
                    })
                    ->values()
                    ->all();

                if (empty($items)) {
                    return null;
                }

                return [
                    'label' => $group['label'],
                    'items' => $items,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * 환불 정책 데이터 (교육과 동일 구조)
     *
     * @return array<int, array<string, string>>
     */
    public function buildSeminarTrainingRefundPolicies(SeminarTraining $program): array
    {
        $policies = [
            ['label' => '2인 1실', 'fee' => $program->refund_twin_fee, 'deadline' => $program->refund_twin_deadline],
            ['label' => '1인실', 'fee' => $program->refund_single_fee, 'deadline' => $program->refund_single_deadline],
            ['label' => '비숙박', 'fee' => $program->refund_no_stay_fee, 'deadline' => $program->refund_no_stay_deadline],
            ['label' => '당일 취소', 'fee' => $program->refund_same_day_fee, 'deadline' => null],
        ];

        return collect($policies)
            ->filter(fn ($policy) => $policy['fee'] !== null)
            ->map(function ($policy) {
                return [
                    'label' => $policy['label'],
                    'fee' => number_format((float) $policy['fee']),
                    'deadline' => $policy['deadline'] ?: '-',
                ];
            })
            ->values()
            ->all();
    }

    private function getSeminarTrainingQuery(?Request $request)
    {
        $query = SeminarTraining::query()
            ->where('is_public', true)
            ->whereNot('application_status', '비공개')
            ->withCount('applications as applications_count');

        if ($request && $request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request && $request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request && $request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }
        $this->applyDateFilter($query, $request);

        return $query;
    }

    private function applyDateFilter($query, ?Request $request): void
    {
        if (!$request || !$request->filled('period_year')) {
            return;
        }
        $dateType = $request->get('date_type', 'application');
        $year = (int) $request->period_year;
        $month = $request->filled('period_month') ? (int) $request->period_month : null;

        $dateColumn = $dateType === 'education' ? 'period_start' : 'application_start';
        $query->whereYear($dateColumn, $year);
        if ($month) {
            $query->whereMonth($dateColumn, $month);
        }
    }

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

    private function getEffectiveApplicationStatusForDisplay(SeminarTraining $program): string
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
        if (!$capacityUnlimited && $capacity > 0 && $enrolled >= $capacity) {
            return '접수마감';
        }
        return '접수중';
    }

    private function assertCapacityAvailable(string $column, int $programId, ?int $capacity, bool $unlimited): void
    {
        if ($unlimited || !$capacity) {
            return;
        }

        $count = EducationApplication::query()
            ->where($column, $programId)
            ->lockForUpdate()
            ->count();

        if ($count >= $capacity) {
            throw ValidationException::withMessages([
                $column => '모집 정원이 마감되었습니다.',
            ]);
        }
    }

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

    private function resolveSeminarTrainingFee(SeminarTraining $program, string $feeType): array
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

    private function lockSeminarTrainingForApplication(int $id): ?SeminarTraining
    {
        return SeminarTraining::query()
            ->withCount('applications as applications_count')
            ->whereKey($id)
            ->lockForUpdate()
            ->first();
    }

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

    private function storeBusinessRegistration(EducationApplication $application, $file): void
    {
        if (!$file) {
            return;
        }

        $this->storeAttachment($application, $file, 'business_registration', 0);
    }

    private function storeAdditionalAttachments(EducationApplication $application, array $files): void
    {
        $order = 1;
        foreach ($files as $file) {
            if ($file) {
                $this->storeAttachment($application, $file, 'attachment', $order++);
            }
        }
    }

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
}
