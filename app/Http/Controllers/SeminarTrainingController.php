<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeminarTraining\SeminarTrainingApplicationRequest;
use App\Models\Member;
use App\Services\SeminarTrainingApplicationService;
use App\Support\TempUploadSessionHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SeminarTrainingController extends Controller
{
    private const APPLICATION_CONFIRMATION_KEY = 'education_certification_application_confirmation';

    public function __construct(
        private SeminarTrainingApplicationService $applicationService
    ) {
    }

    private function menuMeta(): array
    {
        return [
            'gNum' => '02',
            'sNum' => '03',
            'gName' => '세미나 · 해외연수',
            'sName' => '세미나ㆍ해외연수 신청',
        ];
    }

    public function application_st(Request $request)
    {
        $programs = $this->applicationService->getList($request);
        $tabCounts = $this->applicationService->getTabCounts();
        $periodYears = $this->applicationService->getPeriodYears();

        return view('seminars_training.application_st', array_merge(
            $this->menuMeta(),
            compact('programs', 'tabCounts', 'periodYears')
        ));
    }

    public function application_st_view(int $id, Request $request)
    {
        $program = $this->applicationService->getSeminarTrainingDetail($id);
        if (!$program) {
            throw new NotFoundHttpException();
        }
        $member = $request->user('member');
        $alreadyApplied = $member && $this->applicationService->hasMemberAppliedForSeminarTraining($member->id, $program->id);
        $viewData = $this->applicationService->prepareSeminarTrainingDetailView($program, $alreadyApplied);

        return view('seminars_training.application_st_view', array_merge(
            $this->menuMeta(),
            compact('program', 'viewData')
        ));
    }

    public function application_st_apply(Request $request)
    {
        $seminarTrainingId = (int) $request->query('seminar_training_id');
        if ($seminarTrainingId <= 0) {
            throw new NotFoundHttpException();
        }

        $program = $this->applicationService->getSeminarTrainingProgram($seminarTrainingId);
        $member = $request->user('member');

        if ($member && $this->applicationService->hasMemberAppliedForSeminarTraining($member->id, $program->id)) {
            return redirect()
                ->route('seminars_training.application_st')
                ->with('error', '이미 신청한 프로그램입니다.');
        }

        $stApplyDisplay = TempUploadSessionHelper::getDisplayInfo($request, 'seminar_training_apply_temp_files');
        $viewData = array_merge($this->menuMeta(), [
            'program' => $program,
            'member' => $member,
            'feeOptions' => $this->applicationService->buildSeminarTrainingFeeOptions($program),
            'refundPolicies' => $this->applicationService->buildSeminarTrainingRefundPolicies($program),
            'tempFileBusinessRegistration' => $stApplyDisplay['business_registration'] ?? '',
        ]);

        try {
            $this->applicationService->ensureSeminarTrainingCanApply($program);
        } catch (ValidationException $e) {
            return view('seminars_training.application_st_apply', array_merge($viewData, [
                'applicationDisabled' => true,
            ]))->withErrors($e->errors());
        }

        return view('seminars_training.application_st_apply', $viewData);
    }

    public function application_st_apply_end(Request $request)
    {
        $confirmation = $request->session()->pull(self::APPLICATION_CONFIRMATION_KEY);
        if (
            !$confirmation
            || ($confirmation['type'] ?? null) !== 'seminar_training'
        ) {
            return redirect()->route('seminars_training.application_st');
        }

        return view('seminars_training.application_st_apply_end', array_merge(
            $this->menuMeta(),
            ['confirmation' => $confirmation]
        ));
    }

    public function storeSeminarTrainingApplication(SeminarTrainingApplicationRequest $request)
    {
        $program = $this->applicationService->getSeminarTrainingProgram((int) $request->input('seminar_training_id'));
        $member = $request->user('member');

        try {
            $this->applicationService->ensureSeminarTrainingCanApply($program);
            $application = $this->applicationService->submitSeminarTrainingApplication($request, $member);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        TempUploadSessionHelper::clear($request, 'seminar_training_apply_temp_files');

        $request->session()->put(self::APPLICATION_CONFIRMATION_KEY, [
            'type' => 'seminar_training',
            'program_name' => $program->name,
            'application_number' => $application->application_number,
        ]);

        return redirect()->route('seminars_training.application_st_apply_end');
    }

    /**
     * 룸메이트 휴대폰 번호로 회원 조회 (세미나 신청 페이지용)
     * DB 저장 형식과 관계없이 숫자만 비교 (MySQL 8 REGEXP_REPLACE 사용)
     */
    public function checkRoommateMember(Request $request)
    {
        $phone = trim((string) $request->query('phone', ''));
        $digits = Member::normalizePhone($phone);
        if ($digits === '') {
            return response()->json(['found' => false]);
        }

        $currentMemberId = $request->user('member')?->id;
        $member = Member::query()
            ->active()
            ->whereRaw("REGEXP_REPLACE(phone_number, '[^0-9]', '') = ?", [$digits])
            ->first();

        if (!$member) {
            return response()->json(['found' => false]);
        }

        if ($currentMemberId && (int) $member->id === (int) $currentMemberId) {
            return response()->json(['found' => false, 'reason' => 'self']);
        }

        return response()->json([
            'found' => true,
            'id' => $member->id,
            'name' => $member->name,
            'phone' => $member->phone_number ?? '',
        ]);
    }
}
