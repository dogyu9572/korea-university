<?php

namespace App\Services\Backoffice;

use App\Models\OnlineEducation;
use App\Models\OnlineEducationAttachment;
use App\Models\OnlineEducationLecture;
use App\Models\LectureVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OnlineEducationService
{
    /**
     * 온라인 교육 목록을 조회합니다.
     */
    public function getList(Request $request)
    {
        $query = OnlineEducation::query();

        // 접수상태 검색
        if ($request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
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
     * 온라인 교육을 생성합니다.
     */
    public function create(Request $request): OnlineEducation
    {
        DB::beginTransaction();
        try {
            $data = $request->only([
                'education_class',
                'is_public',
                'application_status',
                'name',
                'period_start',
                'period_end',
                'period_time',
                'education_overview',
                'education_schedule',
                'fee_info',
                'refund_policy',
                'curriculum',
                'education_notice',
                'survey_url',
                'certificate_type',
                'completion_hours',
                'capacity',
                'capacity_unlimited',
                'payment_methods',
                'fee',
                'is_free',
                'thumbnail_path',
            ]);

            $data['application_start'] = $this->buildApplicationDatetime(
                $request->application_start_date,
                $request->application_start_hour ?? 0
            );
            $data['application_end'] = $this->buildApplicationDatetime(
                $request->application_end_date,
                $request->application_end_hour ?? 23
            );

            // boolean 처리
            $data['is_public'] = $request->has('is_public') ? (bool)$request->is_public : false;
            $data['capacity_unlimited'] = $request->has('capacity_unlimited') ? (bool)$request->capacity_unlimited : false;
            $data['is_free'] = $request->has('is_free') ? (bool)$request->is_free : false;

            // payment_methods는 모델의 casts가 자동으로 JSON 처리
            if (!$request->has('payment_methods') || !is_array($request->payment_methods)) {
                $data['payment_methods'] = null;
            }

            // 무료인 경우 교육비 0으로 설정
            if ($data['is_free']) {
                $data['fee'] = 0;
            }

            // 썸네일 처리
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('education_programs/thumbnails', 'public');
                $data['thumbnail_path'] = Storage::url($thumbnailPath);
            }

            // 온라인 교육 생성
            $program = OnlineEducation::create($data);

            // 강의영상 저장
            if ($request->has('lectures') && is_array($request->lectures)) {
                $order = 0;
                foreach ($request->lectures as $lectureData) {
                    if (!empty($lectureData['lecture_name'])) {
                        OnlineEducationLecture::create([
                            'online_education_id' => $program->id,
                            'lecture_video_id' => !empty($lectureData['lecture_video_id']) ? (int) $lectureData['lecture_video_id'] : null,
                            'lecture_name' => $lectureData['lecture_name'],
                            'instructor_name' => $lectureData['instructor_name'] ?? '',
                            'lecture_time' => (int)($lectureData['lecture_time'] ?? 0),
                            'order' => $order++,
                        ]);
                    }
                }
            }

            // 첨부파일 저장
            if ($request->hasFile('attachments')) {
                $order = 0;
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('online_educations/attachments', 'public');
                    OnlineEducationAttachment::create([
                        'online_education_id' => $program->id,
                        'path' => Storage::url($path),
                        'name' => $file->getClientOriginalName(),
                        'order' => $order++,
                    ]);
                }
            }

            DB::commit();
            return $program->fresh(['lectures', 'attachments']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('온라인 교육 생성 실패', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * 온라인 교육을 수정합니다.
     */
    public function update(OnlineEducation $program, Request $request): OnlineEducation
    {
        DB::beginTransaction();
        try {
            $data = $request->only([
                'education_class',
                'is_public',
                'application_status',
                'name',
                'period_start',
                'period_end',
                'period_time',
                'education_overview',
                'education_schedule',
                'fee_info',
                'refund_policy',
                'curriculum',
                'education_notice',
                'survey_url',
                'certificate_type',
                'completion_hours',
                'capacity',
                'capacity_unlimited',
                'payment_methods',
                'fee',
                'is_free',
            ]);

            $data['application_start'] = $this->buildApplicationDatetime(
                $request->application_start_date,
                $request->application_start_hour ?? 0
            );
            $data['application_end'] = $this->buildApplicationDatetime(
                $request->application_end_date,
                $request->application_end_hour ?? 23
            );

            // boolean 처리
            $data['is_public'] = $request->has('is_public') ? (bool)$request->is_public : false;
            $data['capacity_unlimited'] = $request->has('capacity_unlimited') ? (bool)$request->capacity_unlimited : false;
            $data['is_free'] = $request->has('is_free') ? (bool)$request->is_free : false;

            // payment_methods는 모델의 casts가 자동으로 JSON 처리
            if (!$request->has('payment_methods') || !is_array($request->payment_methods)) {
                $data['payment_methods'] = null;
            }

            // 무료인 경우 교육비 0으로 설정
            if ($data['is_free']) {
                $data['fee'] = 0;
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

            // 강의영상 처리 (기존 삭제 후 재생성)
            if ($request->has('lectures') && is_array($request->lectures)) {
                $program->lectures()->delete();
                $order = 0;
                foreach ($request->lectures as $lectureData) {
                    if (!empty($lectureData['lecture_name'])) {
                        OnlineEducationLecture::create([
                            'online_education_id' => $program->id,
                            'lecture_video_id' => !empty($lectureData['lecture_video_id']) ? (int) $lectureData['lecture_video_id'] : null,
                            'lecture_name' => $lectureData['lecture_name'],
                            'instructor_name' => $lectureData['instructor_name'] ?? '',
                            'lecture_time' => (int)($lectureData['lecture_time'] ?? 0),
                            'order' => $order++,
                        ]);
                    }
                }
            }

            // 첨부파일 삭제 처리
            if ($request->has('delete_attachments') && is_array($request->delete_attachments)) {
                foreach ($request->delete_attachments as $attachmentId) {
                    if (empty($attachmentId)) {
                        continue;
                    }
                    $attachment = OnlineEducationAttachment::find($attachmentId);
                    if ($attachment && $attachment->online_education_id == $program->id) {
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
                    $path = $file->store('online_educations/attachments', 'public');
                    OnlineEducationAttachment::create([
                        'online_education_id' => $program->id,
                        'path' => Storage::url($path),
                        'name' => $file->getClientOriginalName(),
                        'order' => $order++,
                    ]);
                }
            }

            DB::commit();
            return $program->fresh(['lectures', 'attachments']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('온라인 교육 수정 실패', ['error' => $e->getMessage(), 'program_id' => $program->id]);
            throw $e;
        }
    }

    /**
     * 온라인 교육을 삭제합니다.
     */
    public function delete(OnlineEducation $program): bool
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
            $program->lectures()->delete();
            $program->attachments()->delete();

            // 프로그램 삭제
            $program->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('온라인 교육 삭제 실패', ['error' => $e->getMessage(), 'program_id' => $program->id]);
            throw $e;
        }
    }

    /**
     * 강의영상을 검색합니다. (LectureVideo 모델에서 검색)
     */
    public function searchLectures(Request $request)
    {
        $query = LectureVideo::select('id', 'title', 'instructor_name', 'lecture_time', 'video_url', 'created_at')
            ->where('is_active', true);

        // 검색 필터
        if ($request->filled('search_type') && $request->filled('search_term')) {
            $searchTerm = $request->search_term;
            $query->where(function($q) use ($request, $searchTerm) {
                if ($request->search_type === '전체' || $request->search_type === '강의제목') {
                    $q->where('title', 'like', '%' . $searchTerm . '%');
                }
                if ($request->search_type === '전체' || $request->search_type === '강사명') {
                    $q->orWhere('instructor_name', 'like', '%' . $searchTerm . '%');
                }
            });
        }

        // 정렬 (최신순)
        $query->orderBy('created_at', 'desc');

        // 페이징
        $perPage = $request->get('per_page', 20);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;
        return $query->paginate($perPage)->withQueryString();
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
     * 온라인 교육 폼에 넘길 데이터를 반환합니다. (MVC: 뷰 로직 제거용)
     */
    public function getFormData(?OnlineEducation $program): array
    {
        $isEdit = $program && $program->exists;
        $program = $program ?? new OnlineEducation();
        $attachments = $isEdit ? $program->attachments : collect([]);
        $lectures = $isEdit ? $program->lectures()->with('lectureVideo')->orderBy('order')->get() : collect([]);

        $appStart = $program->application_start;
        $appEnd = $program->application_end;

        return [
            'program' => $program,
            'isEdit' => $isEdit,
            'attachments' => $attachments,
            'lectures' => $lectures,
            'application_start_date' => old('application_start_date', $appStart ? $appStart->format('Y-m-d') : ''),
            'application_start_hour' => (int) old('application_start_hour', $appStart ? (int) $appStart->format('H') : 0),
            'application_end_date' => old('application_end_date', $appEnd ? $appEnd->format('Y-m-d') : ''),
            'application_end_hour' => (int) old('application_end_hour', $appEnd ? (int) $appEnd->format('H') : 23),
        ];
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

    /**
     * 강의 삭제
     */
    public function deleteLecture(int $id): void
    {
        $lecture = OnlineEducationLecture::findOrFail($id);
        $lecture->delete();
    }
}
