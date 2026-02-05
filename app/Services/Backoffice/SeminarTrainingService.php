<?php

namespace App\Services\Backoffice;

use App\Models\SeminarTraining;
use App\Models\SeminarTrainingAttachment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SeminarTrainingService
{
    private const TYPES = ['세미나', '해외연수'];

    /**
     * 세미나/해외연수 목록을 조회합니다.
     */
    public function getList(Request $request): LengthAwarePaginator
    {
        $query = SeminarTraining::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }
        if ($request->filled('period_start')) {
            $query->where('period_start', '>=', $request->period_start);
        }
        if ($request->filled('period_end')) {
            $query->where('period_end', '<=', $request->period_end);
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
        $perPage = in_array((int) $perPage, [10, 20, 50, 100]) ? (int) $perPage : 20;
        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 세미나/해외연수를 생성합니다.
     */
    public function create(Request $request): SeminarTraining
    {
        $this->ensureType($request->type);

        DB::beginTransaction();
        try {
            $data = $request->only([
                'type',
                'education_class',
                'total_sessions_class',
                'is_public',
                'application_status',
                'name',
                'period_start',
                'period_end',
                'period_time',
                'is_accommodation',
                'location',
                'target',
                'education_overview',
                'education_schedule',
                'fee_info',
                'refund_policy',
                'curriculum',
                'education_notice',
                'completion_criteria',
                'survey_url',
                'certificate_type',
                'completion_hours',
                'annual_fee',
                'capacity',
                'capacity_unlimited',
                'capacity_per_school',
                'capacity_per_school_unlimited',
                'payment_methods',
                'deposit_account',
                'deposit_deadline_days',
                'fee_member_twin',
                'fee_member_single',
                'fee_member_no_stay',
                'fee_guest_twin',
                'fee_guest_single',
                'fee_guest_no_stay',
                'refund_twin_fee',
                'refund_single_fee',
                'refund_no_stay_fee',
                'refund_same_day_fee',
            ]);

            $data['application_start'] = $this->buildApplicationDatetime(
                $request->application_start_date,
                $request->application_start_hour ?? 0
            );
            $data['application_end'] = $this->buildApplicationDatetime(
                $request->application_end_date,
                $request->application_end_hour ?? 23
            );
            $data['refund_twin_deadline'] = $this->buildRefundDeadline($request->refund_twin_deadline_days);
            $data['refund_single_deadline'] = $this->buildRefundDeadline($request->refund_single_deadline_days);
            $data['refund_no_stay_deadline'] = $this->buildRefundDeadline($request->refund_no_stay_deadline_days);

            $data['is_public'] = $request->has('is_public') ? (bool) $request->is_public : false;
            $data['is_accommodation'] = $request->has('is_accommodation') ? (bool) $request->is_accommodation : false;
            $data['capacity_unlimited'] = $request->has('capacity_unlimited') ? (bool) $request->capacity_unlimited : false;
            $data['capacity_per_school_unlimited'] = $request->has('capacity_per_school_unlimited') ? (bool) $request->capacity_per_school_unlimited : false;

            if (! $request->has('payment_methods') || ! is_array($request->payment_methods)) {
                $data['payment_methods'] = null;
            }
            if ($request->deposit_deadline_days === '' || $request->deposit_deadline_days === null) {
                $data['deposit_deadline_days'] = null;
            }

            $program = SeminarTraining::create($data);

            if ($request->hasFile('attachments')) {
                $order = 0;
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('seminar_trainings/attachments', 'public');
                    SeminarTrainingAttachment::create([
                        'seminar_training_id' => $program->id,
                        'path' => Storage::url($path),
                        'name' => $file->getClientOriginalName(),
                        'order' => $order++,
                    ]);
                }
            }

            DB::commit();
            return $program;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('세미나/해외연수 생성 실패', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * 세미나/해외연수를 수정합니다.
     */
    public function update(SeminarTraining $program, Request $request): SeminarTraining
    {
        $this->ensureProgramType($program);
        $this->ensureType($request->type);

        DB::beginTransaction();
        try {
            $data = $request->only([
                'type',
                'education_class',
                'total_sessions_class',
                'is_public',
                'application_status',
                'name',
                'period_start',
                'period_end',
                'period_time',
                'is_accommodation',
                'location',
                'target',
                'education_overview',
                'education_schedule',
                'fee_info',
                'refund_policy',
                'curriculum',
                'education_notice',
                'completion_criteria',
                'survey_url',
                'certificate_type',
                'completion_hours',
                'annual_fee',
                'capacity',
                'capacity_unlimited',
                'capacity_per_school',
                'capacity_per_school_unlimited',
                'payment_methods',
                'deposit_account',
                'deposit_deadline_days',
                'fee_member_twin',
                'fee_member_single',
                'fee_member_no_stay',
                'fee_guest_twin',
                'fee_guest_single',
                'fee_guest_no_stay',
                'refund_twin_fee',
                'refund_single_fee',
                'refund_no_stay_fee',
                'refund_same_day_fee',
            ]);

            $data['application_start'] = $this->buildApplicationDatetime(
                $request->application_start_date,
                $request->application_start_hour ?? 0
            );
            $data['application_end'] = $this->buildApplicationDatetime(
                $request->application_end_date,
                $request->application_end_hour ?? 23
            );
            $data['refund_twin_deadline'] = $this->buildRefundDeadline($request->refund_twin_deadline_days);
            $data['refund_single_deadline'] = $this->buildRefundDeadline($request->refund_single_deadline_days);
            $data['refund_no_stay_deadline'] = $this->buildRefundDeadline($request->refund_no_stay_deadline_days);

            $data['is_public'] = $request->has('is_public') ? (bool) $request->is_public : false;
            $data['is_accommodation'] = $request->has('is_accommodation') ? (bool) $request->is_accommodation : false;
            $data['capacity_unlimited'] = $request->has('capacity_unlimited') ? (bool) $request->capacity_unlimited : false;
            $data['capacity_per_school_unlimited'] = $request->has('capacity_per_school_unlimited') ? (bool) $request->capacity_per_school_unlimited : false;

            if (! $request->has('payment_methods') || ! is_array($request->payment_methods)) {
                $data['payment_methods'] = null;
            }
            if ($request->deposit_deadline_days === '' || $request->deposit_deadline_days === null) {
                $data['deposit_deadline_days'] = null;
            }

            $program->update($data);

            if ($request->has('delete_attachments') && is_array($request->delete_attachments)) {
                foreach ($request->delete_attachments as $id) {
                    if (empty($id)) {
                        continue;
                    }
                    $att = SeminarTrainingAttachment::find($id);
                    if ($att && $att->seminar_training_id === (int) $program->id) {
                        $this->deleteFile($att->path);
                        $att->delete();
                    }
                }
            }
            if ($request->hasFile('attachments')) {
                $maxOrder = (int) $program->attachments()->max('order');
                $order = $maxOrder + 1;
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('seminar_trainings/attachments', 'public');
                    SeminarTrainingAttachment::create([
                        'seminar_training_id' => $program->id,
                        'path' => Storage::url($path),
                        'name' => $file->getClientOriginalName(),
                        'order' => $order++,
                    ]);
                }
            }

            DB::commit();
            return $program->fresh(['attachments']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('세미나/해외연수 수정 실패', ['program_id' => $program->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * 세미나/해외연수를 삭제합니다.
     */
    public function delete(SeminarTraining $program): bool
    {
        $this->ensureProgramType($program);

        DB::beginTransaction();
        try {
            foreach ($program->attachments as $att) {
                $this->deleteFile($att->path);
            }
            $program->schedules()->delete();
            $program->attachments()->delete();
            $program->delete();
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('세미나/해외연수 삭제 실패', ['program_id' => $program->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    private function ensureType(?string $type): void
    {
        if (! in_array($type, self::TYPES, true)) {
            throw new \InvalidArgumentException('구분은 세미나 또는 해외연수만 허용됩니다.');
        }
    }

    private function ensureProgramType(SeminarTraining $program): void
    {
        if (! in_array($program->type, self::TYPES, true)) {
            abort(404);
        }
    }

    private function buildApplicationDatetime(?string $date, $hour): ?string
    {
        if (! $date) {
            return null;
        }
        $h = (int) ($hour ?? 0);
        $h = $h >= 0 && $h <= 23 ? $h : 0;
        return $date . ' ' . sprintf('%02d', $h) . ':00:00';
    }

    private function buildRefundDeadline($days): ?string
    {
        if ($days === null || $days === '') {
            return null;
        }
        $d = (int) $days;
        if ($d < 1 || $d > 30) {
            return null;
        }
        return '교육 시작일 기준 ' . $d . '일 전';
    }

    private function deleteFile(string $path): void
    {
        try {
            $rel = str_replace('/storage/', '', $path);
            if (Storage::disk('public')->exists($rel)) {
                Storage::disk('public')->delete($rel);
            }
        } catch (\Throwable $e) {
            Log::warning('파일 삭제 실패', ['path' => $path, 'error' => $e->getMessage()]);
        }
    }
}
