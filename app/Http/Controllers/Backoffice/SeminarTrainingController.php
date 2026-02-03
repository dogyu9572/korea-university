<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Requests\Backoffice\SeminarTrainingStoreRequest;
use App\Http\Requests\Backoffice\SeminarTrainingUpdateRequest;
use App\Services\Backoffice\SeminarTrainingService;
use App\Models\SeminarTraining;
use Illuminate\Http\Request;

class SeminarTrainingController extends BaseController
{
    public function __construct(
        protected SeminarTrainingService $seminarTrainingService
    ) {}

    public function index(Request $request)
    {
        $programs = $this->seminarTrainingService->getList($request);
        return $this->view('backoffice.seminar-trainings.index', compact('programs'));
    }

    public function create()
    {
        $program = new SeminarTraining();
        $formData = $this->prepareFormData($program);
        return $this->view('backoffice.seminar-trainings.create', $formData);
    }

    public function store(SeminarTrainingStoreRequest $request)
    {
        try {
            $this->seminarTrainingService->create($request);
            return redirect()->route('backoffice.seminar-trainings.index')
                ->with('success', '세미나/해외연수가 등록되었습니다.');
        } catch (\Throwable $e) {
            return redirect()->route('backoffice.seminar-trainings.create')
                ->with('error', '등록 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(SeminarTraining $seminar_training)
    {
        $seminar_training->load(['attachments']);
        $formData = $this->prepareFormData($seminar_training);
        return $this->view('backoffice.seminar-trainings.edit', $formData);
    }

    public function update(SeminarTrainingUpdateRequest $request, SeminarTraining $seminar_training)
    {
        try {
            $this->seminarTrainingService->update($seminar_training, $request);
            return redirect()->route('backoffice.seminar-trainings.index')
                ->with('success', '세미나/해외연수가 수정되었습니다.');
        } catch (\Throwable $e) {
            return redirect()->route('backoffice.seminar-trainings.edit', $seminar_training)
                ->with('error', '수정 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(SeminarTraining $seminar_training)
    {
        try {
            $this->seminarTrainingService->delete($seminar_training);
            return redirect()->route('backoffice.seminar-trainings.index')
                ->with('success', '세미나/해외연수가 삭제되었습니다.');
        } catch (\Throwable $e) {
            return redirect()->route('backoffice.seminar-trainings.index')
                ->with('error', '삭제 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    /**
     * 폼에 필요한 데이터를 준비합니다.
     */
    private function prepareFormData(SeminarTraining $program): array
    {
        $isEdit = $program->exists;
        $attachments = $isEdit ? $program->attachments : collect([]);

        // 신청 기간 날짜/시간 분리
        $appStart = $program->application_start;
        $appEnd = $program->application_end;
        $appStartDate = old('application_start_date', $appStart ? $appStart->format('Y-m-d') : '');
        $appStartHour = old('application_start_hour', $appStart ? (int) $appStart->format('H') : 0);
        $appEndDate = old('application_end_date', $appEnd ? $appEnd->format('Y-m-d') : '');
        $appEndHour = old('application_end_hour', $appEnd ? (int) $appEnd->format('H') : 23);

        // 환불 기한 파싱
        $refundTwin = old('refund_twin_deadline_days', $this->parseRefundDeadline($program->refund_twin_deadline));
        $refundSingle = old('refund_single_deadline_days', $this->parseRefundDeadline($program->refund_single_deadline));
        $refundNoStay = old('refund_no_stay_deadline_days', $this->parseRefundDeadline($program->refund_no_stay_deadline));

        return [
            'program' => $program,
            'isEdit' => $isEdit,
            'attachments' => $attachments,
            'appStartDate' => $appStartDate,
            'appStartHour' => $appStartHour,
            'appEndDate' => $appEndDate,
            'appEndHour' => $appEndHour,
            'refundTwin' => $refundTwin,
            'refundSingle' => $refundSingle,
            'refundNoStay' => $refundNoStay,
        ];
    }

    /**
     * 환불 기한 문자열("N일 전")을 일수로 파싱합니다.
     */
    private function parseRefundDeadline(?string $value): ?int
    {
        if (! $value) {
            return null;
        }
        if (preg_match('/(\d+)\s*일\s*전/u', $value, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }
}

