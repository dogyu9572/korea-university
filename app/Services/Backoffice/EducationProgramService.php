<?php

namespace App\Services\Backoffice;

use App\Models\EducationProgram;
use App\Models\EducationSchedule;
use App\Models\EducationAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EducationProgramService
{
    /**
     * 교육 프로그램 목록을 조회합니다.
     */
    public function getList(Request $request)
    {
        $query = EducationProgram::query();

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

        // 정렬 (최신순)
        $query->orderBy('created_at', 'desc');

        // 페이징
        $perPage = $request->get('per_page', 20);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;
        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 교육 프로그램을 생성합니다.
     */
    public function create(Request $request): EducationProgram
    {
        DB::beginTransaction();
        try {
            $data = $request->only([
                'education_type',
                'education_class',
                'is_public',
                'application_status',
                'name',
                'period_start',
                'period_end',
                'period_time',
                'is_accommodation',
                'location',
                'target',
                'content',
                'completion_criteria',
                'survey_url',
                'certificate_type',
                'completion_hours',
                'capacity',
                'capacity_unlimited',
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

            // boolean 처리
            $data['is_public'] = $request->has('is_public') ? (bool)$request->is_public : false;
            $data['is_accommodation'] = $request->has('is_accommodation') ? (bool)$request->is_accommodation : false;
            $data['capacity_unlimited'] = $request->has('capacity_unlimited') ? (bool)$request->capacity_unlimited : false;

            // payment_methods는 모델의 casts가 자동으로 JSON 처리
            if (!$request->has('payment_methods') || !is_array($request->payment_methods)) {
                $data['payment_methods'] = null;
            }

            if ($request->deposit_deadline_days === '' || $request->deposit_deadline_days === null) {
                $data['deposit_deadline_days'] = null;
            }

            // 썸네일 처리
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('education_programs/thumbnails', 'public');
                $data['thumbnail_path'] = Storage::url($thumbnailPath);
            }

            // 교육 프로그램 생성
            $program = EducationProgram::create($data);

            // 교육 일정 저장
            if ($request->has('schedules') && is_array($request->schedules)) {
                foreach ($request->schedules as $scheduleData) {
                    if (!empty($scheduleData['class_name']) || !empty($scheduleData['schedule_start'])) {
                        EducationSchedule::create([
                            'education_program_id' => $program->id,
                            'class_name' => $scheduleData['class_name'] ?? null,
                            'schedule_start' => $scheduleData['schedule_start'] ?? null,
                            'schedule_end' => $scheduleData['schedule_end'] ?? null,
                            'location' => $scheduleData['location'] ?? null,
                            'capacity' => $scheduleData['capacity'] ?? null,
                            'note' => $scheduleData['note'] ?? null,
                        ]);
                    }
                }
            }

            // 첨부파일 저장
            if ($request->hasFile('attachments')) {
                $order = 0;
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('education_programs/attachments', 'public');
                    EducationAttachment::create([
                        'education_program_id' => $program->id,
                        'path' => Storage::url($path),
                        'name' => $file->getClientOriginalName(),
                        'order' => $order++,
                    ]);
                }
            }

            DB::commit();
            return $program;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('교육 프로그램 생성 실패', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * 교육 프로그램을 수정합니다.
     */
    public function update(EducationProgram $program, Request $request): EducationProgram
    {
        DB::beginTransaction();
        try {
            $data = $request->only([
                'education_type',
                'education_class',
                'is_public',
                'application_status',
                'name',
                'period_start',
                'period_end',
                'period_time',
                'is_accommodation',
                'location',
                'target',
                'content',
                'completion_criteria',
                'survey_url',
                'certificate_type',
                'completion_hours',
                'capacity',
                'capacity_unlimited',
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

            // boolean 처리
            $data['is_public'] = $request->has('is_public') ? (bool)$request->is_public : false;
            $data['is_accommodation'] = $request->has('is_accommodation') ? (bool)$request->is_accommodation : false;
            $data['capacity_unlimited'] = $request->has('capacity_unlimited') ? (bool)$request->capacity_unlimited : false;

            // payment_methods는 모델의 casts가 자동으로 JSON 처리
            if (!$request->has('payment_methods') || !is_array($request->payment_methods)) {
                $data['payment_methods'] = null;
            }

            if ($request->deposit_deadline_days === '' || $request->deposit_deadline_days === null) {
                $data['deposit_deadline_days'] = null;
            }

            // 썸네일 삭제 처리
            if ($request->has('delete_thumbnail') && $request->delete_thumbnail == '1') {
                if ($program->thumbnail_path) {
                    $this->deleteFile($program->thumbnail_path);
                }
                $data['thumbnail_path'] = null;
            }
            
            // 썸네일 업로드 처리
            if ($request->hasFile('thumbnail')) {
                // 기존 썸네일 삭제
                if ($program->thumbnail_path) {
                    $this->deleteFile($program->thumbnail_path);
                }
                $thumbnailPath = $request->file('thumbnail')->store('education_programs/thumbnails', 'public');
                $data['thumbnail_path'] = Storage::url($thumbnailPath);
            }

            // 교육 프로그램 업데이트
            $program->update($data);

            // 교육 일정 처리 (기존 삭제 후 재생성)
            if ($request->has('schedules')) {
                $program->schedules()->delete();
                if (is_array($request->schedules)) {
                    foreach ($request->schedules as $scheduleData) {
                        if (!empty($scheduleData['class_name']) || !empty($scheduleData['schedule_start'])) {
                            EducationSchedule::create([
                                'education_program_id' => $program->id,
                                'class_name' => $scheduleData['class_name'] ?? null,
                                'schedule_start' => $scheduleData['schedule_start'] ?? null,
                                'schedule_end' => $scheduleData['schedule_end'] ?? null,
                                'location' => $scheduleData['location'] ?? null,
                                'capacity' => $scheduleData['capacity'] ?? null,
                                'note' => $scheduleData['note'] ?? null,
                            ]);
                        }
                    }
                }
            }

            // 첨부파일 삭제 처리
            if ($request->has('delete_attachments') && is_array($request->delete_attachments)) {
                foreach ($request->delete_attachments as $attachmentId) {
                    if (empty($attachmentId)) {
                        continue;
                    }
                    $attachment = EducationAttachment::find($attachmentId);
                    if ($attachment && $attachment->education_program_id == $program->id) {
                        $this->deleteFile($attachment->path);
                        $attachment->delete();
                    }
                }
            }

            // 첨부파일 추가
            if ($request->hasFile('attachments')) {
                $maxOrder = $program->attachments()->max('order') ?? -1;
                $order = $maxOrder + 1;
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('education_programs/attachments', 'public');
                    EducationAttachment::create([
                        'education_program_id' => $program->id,
                        'path' => Storage::url($path),
                        'name' => $file->getClientOriginalName(),
                        'order' => $order++,
                    ]);
                }
            }

            DB::commit();
            return $program->fresh(['schedules', 'attachments']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('교육 프로그램 수정 실패', ['error' => $e->getMessage(), 'program_id' => $program->id]);
            throw $e;
        }
    }

    /**
     * 교육 프로그램을 삭제합니다.
     */
    public function delete(EducationProgram $program): bool
    {
        DB::beginTransaction();
        try {
            // 첨부파일 삭제
            foreach ($program->attachments as $attachment) {
                $this->deleteFile($attachment->path);
            }

            // 썸네일 삭제
            if ($program->thumbnail_path) {
                $this->deleteFile($program->thumbnail_path);
            }

            // 관계 데이터 삭제 (cascade로 자동 삭제되지만 명시적으로)
            $program->schedules()->delete();
            $program->attachments()->delete();

            // 프로그램 삭제
            $program->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('교육 프로그램 삭제 실패', ['error' => $e->getMessage(), 'program_id' => $program->id]);
            throw $e;
        }
    }

    /**
     * 신청기간 날짜+시간을 datetime 문자열로 만듭니다.
     */
    private function buildApplicationDatetime(?string $date, $hour): ?string
    {
        if (!$date) {
            return null;
        }
        $h = (int) ($hour ?? 0);
        $h = $h >= 0 && $h <= 23 ? $h : 0;
        return $date . ' ' . sprintf('%02d', $h) . ':00:00';
    }

    /**
     * 취소 기한 일수로 "교육 시작일 기준 N일 전" 문자열을 만듭니다.
     */
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

    /**
     * 파일을 삭제합니다.
     */
    private function deleteFile(string $filePath): void
    {
        try {
            $relativePath = str_replace('/storage/', '', $filePath);
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
        } catch (\Exception $e) {
            Log::error('파일 삭제 실패', ['path' => $filePath, 'error' => $e->getMessage()]);
        }
    }
}
