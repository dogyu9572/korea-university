<?php

namespace App\Http\Controllers;

use App\Http\Requests\EducationCertification\CertificationApplicationRequest;
use App\Http\Requests\EducationCertification\EducationProgramApplicationRequest;
use App\Http\Requests\EducationCertification\OnlineEducationApplicationRequest;
use App\Models\Certification;
use App\Models\Education;
use App\Models\OnlineEducation;
use App\Services\EducationCertificationApplicationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EducationCertificationController extends Controller
{
    private const APPLICATION_CONFIRMATION_KEY = 'education_certification_application_confirmation';

    public function __construct(
        private EducationCertificationApplicationService $applicationService
    ) {
    }

    private function menuMeta(): array
    {
        return [
            'gNum' => '01',
            'sNum' => '03',
            'gName' => '교육 · 자격증',
            'sName' => '교육ㆍ자격증 신청',
        ];
    }

    public function application_ec(Request $request)
    {
        $allowedTabs = ['all', 'education', 'certification', 'online'];
        $currentTab = $request->get('tab', 'all');
        $currentTab = in_array($currentTab, $allowedTabs, true) ? $currentTab : 'all';

        $programs = $this->applicationService->getList($request);
        $tabCounts = $this->applicationService->getTabCounts();
        $periodYears = $this->applicationService->getPeriodYears();

        return view('education_certification.application_ec', array_merge(
            $this->menuMeta(),
            compact('programs', 'tabCounts', 'periodYears', 'currentTab')
        ));
    }

    public function application_ec_view(int $id)
    {
        $education = $this->applicationService->getEducationDetail($id);
        if (!$education) {
            throw new NotFoundHttpException();
        }
        $viewData = $this->applicationService->prepareEducationDetailView($education);

        return view('education_certification.application_ec_view', array_merge(
            $this->menuMeta(),
            compact('education', 'viewData')
        ));
    }

    public function application_ec_apply(Request $request)
    {
        $educationId = (int) $request->query('education_id');
        if ($educationId <= 0) {
            throw new NotFoundHttpException();
        }

        $education = $this->applicationService->getEducationProgram($educationId);
        $member = $request->user('member');

        if ($member && $this->applicationService->hasMemberAppliedForEducation($member->id, $educationId)) {
            return redirect()
                ->route('education_certification.application_ec')
                ->with('error', '이미 신청한 교육입니다.');
        }

        $memberSchoolType = $this->applicationService->getMemberSchoolType($member);
        $viewData = array_merge($this->menuMeta(), [
            'education' => $education,
            'member' => $member,
            'memberSchoolType' => $memberSchoolType,
            'feeOptions' => $this->buildEducationFeeOptions($education),
            'refundPolicies' => $this->buildEducationRefundPolicies($education),
        ]);

        try {
            $this->applicationService->ensureEducationCanApply($education);
        } catch (ValidationException $e) {
            return view('education_certification.application_ec_apply', array_merge($viewData, [
                'applicationDisabled' => true,
            ]))->withErrors($e->errors());
        }

        return view('education_certification.application_ec_apply', $viewData);
    }

    public function application_ec_apply_end(Request $request)
    {
        $confirmation = $request->session()->pull(self::APPLICATION_CONFIRMATION_KEY);
        if (
            !$confirmation
            || !in_array($confirmation['type'] ?? null, ['education', 'online'], true)
        ) {
            return redirect()->route('education_certification.application_ec');
        }

        return view('education_certification.application_ec_apply_end', array_merge(
            $this->menuMeta(),
            ['confirmation' => $confirmation]
        ));
    }

    public function application_ec_view_type2(int $id)
    {
        $certification = $this->applicationService->getCertificationDetail($id);
        if (!$certification) {
            throw new NotFoundHttpException();
        }
        $viewData = $this->applicationService->prepareCertificationDetailView($certification);

        return view('education_certification.application_ec_view_type2', array_merge(
            $this->menuMeta(),
            compact('certification', 'viewData')
        ));
    }

    public function application_ec_view_online(int $id)
    {
        $onlineEducation = $this->applicationService->getOnlineEducationDetail($id);
        if (!$onlineEducation) {
            throw new NotFoundHttpException();
        }
        $viewData = $this->applicationService->prepareOnlineEducationDetailView($onlineEducation);

        return view('education_certification.application_ec_view_online', array_merge(
            $this->menuMeta(),
            compact('onlineEducation', 'viewData')
        ));
    }

    public function application_ec_receipt(Request $request)
    {
        $certificationId = (int) $request->query('certification_id');
        if ($certificationId <= 0) {
            throw new NotFoundHttpException();
        }

        $certification = $this->applicationService->getCertificationProgram($certificationId);
        $member = $request->user('member');
        $examVenues = $this->applicationService->getCertificationExamVenues($certification);
        $viewData = array_merge($this->menuMeta(), [
            'certification' => $certification,
            'member' => $member,
            'examVenues' => $examVenues,
        ]);

        try {
            $this->applicationService->ensureCertificationCanApply($certification);
        } catch (ValidationException $e) {
            return view('education_certification.application_ec_receipt', array_merge($viewData, [
                'applicationDisabled' => true,
            ]))->withErrors($e->errors());
        }

        return view('education_certification.application_ec_receipt', $viewData);
    }

    public function application_ec_receipt_end(Request $request)
    {
        $confirmation = $request->session()->pull(self::APPLICATION_CONFIRMATION_KEY);
        if (!$confirmation || ($confirmation['type'] ?? null) !== 'certification') {
            return redirect()->route('education_certification.application_ec');
        }

        return view('education_certification.application_ec_receipt_end', array_merge(
            $this->menuMeta(),
            ['confirmation' => $confirmation]
        ));
    }

    public function application_ec_e_learning(Request $request)
    {
        $onlineEducationId = (int) $request->query('online_education_id');
        if ($onlineEducationId <= 0) {
            throw new NotFoundHttpException();
        }

        $onlineEducation = $this->applicationService->getOnlineEducationProgram($onlineEducationId);
        $member = $request->user('member');

        if ($member && $this->applicationService->hasMemberAppliedForOnlineEducation($member->id, $onlineEducationId)) {
            return redirect()
                ->route('education_certification.application_ec', ['tab' => 'online'])
                ->with('error', '이미 해당 온라인교육을 신청하셨습니다.');
        }

        $cashReceiptDefaults = $member
            ? $this->applicationService->getMemberLastCashReceiptPreferences($member->id)
            : ['has_cash_receipt' => false, 'cash_receipt_purpose' => null, 'cash_receipt_number' => null];

        $viewData = array_merge($this->menuMeta(), [
            'onlineEducation' => $onlineEducation,
            'member' => $member,
            'cashReceiptDefaults' => $cashReceiptDefaults,
        ]);

        try {
            $this->applicationService->ensureOnlineEducationCanApply($onlineEducation);
        } catch (ValidationException $e) {
            return view('education_certification.application_ec_e_learning', array_merge($viewData, [
                'applicationDisabled' => true,
            ]))->withErrors($e->errors());
        }

        return view('education_certification.application_ec_e_learning', $viewData);
    }

    public function storeEducationApplication(EducationProgramApplicationRequest $request)
    {
        $education = $this->applicationService->getEducationProgram((int) $request->input('education_id'));
        $member = $request->user('member');

        try {
            $this->applicationService->ensureEducationCanApply($education);
            $application = $this->applicationService->submitEducationApplication($request, $member);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $request->session()->put(self::APPLICATION_CONFIRMATION_KEY, [
            'type' => 'education',
            'program_name' => $education->name,
            'application_number' => $application->application_number,
        ]);

        return redirect()->route('education_certification.application_ec_apply_end');
    }

    public function storeCertificationApplication(CertificationApplicationRequest $request)
    {
        $certification = $this->applicationService->getCertificationProgram((int) $request->input('certification_id'));
        $member = $request->user('member');

        try {
            $this->applicationService->ensureCertificationCanApply($certification);
            $application = $this->applicationService->submitCertificationApplication($request, $member);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $request->session()->put(self::APPLICATION_CONFIRMATION_KEY, [
            'type' => 'certification',
            'program_name' => $certification->name,
            'application_number' => $application->application_number,
        ]);

        return redirect()->route('education_certification.application_ec_receipt_end');
    }

    public function storeOnlineEducationApplication(OnlineEducationApplicationRequest $request)
    {
        $onlineEducation = $this->applicationService->getOnlineEducationProgram((int) $request->input('online_education_id'));
        $member = $request->user('member');

        try {
            $this->applicationService->ensureOnlineEducationCanApply($onlineEducation);
            $application = $this->applicationService->submitOnlineEducationApplication($request, $member);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $request->session()->put(self::APPLICATION_CONFIRMATION_KEY, [
            'type' => 'online',
            'program_name' => $onlineEducation->name,
            'application_number' => $application->application_number,
        ]);

        return redirect()->route('education_certification.application_ec_apply_end');
    }

    /**
     * 교육 참가비 옵션 데이터를 구성합니다.
     *
     * @return array<int, array<string, mixed>>
     */
    private function buildEducationFeeOptions(Education $education): array
    {
        $groups = [
            [
                'label' => '회원교(1인당)',
                'items' => [
                    ['key' => 'member_twin', 'label' => '2인 1실', 'amount' => $education->fee_member_twin],
                    ['key' => 'member_single', 'label' => '1인실', 'amount' => $education->fee_member_single],
                    ['key' => 'member_no_stay', 'label' => '비숙박', 'amount' => $education->fee_member_no_stay],
                ],
            ],
            [
                'label' => '비회원교(1인당)',
                'items' => [
                    ['key' => 'guest_twin', 'label' => '2인 1실', 'amount' => $education->fee_guest_twin],
                    ['key' => 'guest_single', 'label' => '1인실', 'amount' => $education->fee_guest_single],
                    ['key' => 'guest_no_stay', 'label' => '비숙박', 'amount' => $education->fee_guest_no_stay],
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
     * 교육 환불 정책 데이터를 구성합니다.
     *
     * @return array<int, array<string, string>>
     */
    private function buildEducationRefundPolicies(Education $education): array
    {
        $policies = [
            [
                'label' => '2인 1실',
                'fee' => $education->refund_twin_fee,
                'deadline' => $education->refund_twin_deadline,
            ],
            [
                'label' => '1인실',
                'fee' => $education->refund_single_fee,
                'deadline' => $education->refund_single_deadline,
            ],
            [
                'label' => '비숙박',
                'fee' => $education->refund_no_stay_fee,
                'deadline' => $education->refund_no_stay_deadline,
            ],
            [
                'label' => '당일 취소',
                'fee' => $education->refund_same_day_fee,
                'deadline' => null,
            ],
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
}
