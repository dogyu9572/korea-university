<?php

namespace App\Services\Backoffice;

use App\Models\LectureVideo;
use App\Models\LectureVideoAttachment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LectureVideoService
{
    /**
     * 강의 영상 목록을 조회합니다.
     */
    public function getList(Request $request): LengthAwarePaginator
    {
        $query = LectureVideo::query();

        // 교육기간 검색 (등록일 기준)
        if ($request->filled('period_start')) {
            $query->whereDate('created_at', '>=', $request->period_start);
        }
        if ($request->filled('period_end')) {
            $query->whereDate('created_at', '<=', $request->period_end);
        }

        // 검색어 검색 (강의제목, 강사명)
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
        $perPage = in_array((int) $perPage, [10, 20, 50, 100]) ? (int) $perPage : 20;
        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 강의 영상을 생성합니다.
     */
    public function create(Request $request): LectureVideo
    {
        DB::beginTransaction();
        try {
            $data = $request->only([
                'title',
                'lecture_time',
                'instructor_name',
                'video_url',
                'is_active',
            ]);

            $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : true;

            // 썸네일 업로드
            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('lecture_videos/thumbnails', 'public');
                $data['thumbnail_path'] = Storage::url($path);
            }

            $lectureVideo = LectureVideo::create($data);

            // 첨부파일 업로드
            if ($request->hasFile('attachments')) {
                $order = 0;
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('lecture_videos/attachments', 'public');
                    LectureVideoAttachment::create([
                        'lecture_video_id' => $lectureVideo->id,
                        'path' => Storage::url($path),
                        'name' => $file->getClientOriginalName(),
                        'order' => $order++,
                    ]);
                }
            }

            DB::commit();
            return $lectureVideo;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('강의 영상 생성 실패', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * 강의 영상을 수정합니다.
     */
    public function update(LectureVideo $lectureVideo, Request $request): LectureVideo
    {
        DB::beginTransaction();
        try {
            $data = $request->only([
                'title',
                'lecture_time',
                'instructor_name',
                'video_url',
                'is_active',
            ]);

            $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : false;

            // 썸네일 삭제
            if ($request->has('delete_thumbnail') && $request->delete_thumbnail === '1') {
                if ($lectureVideo->thumbnail_path) {
                    $this->deleteFile($lectureVideo->thumbnail_path);
                }
                $data['thumbnail_path'] = null;
            }

            // 썸네일 업로드
            if ($request->hasFile('thumbnail')) {
                if ($lectureVideo->thumbnail_path) {
                    $this->deleteFile($lectureVideo->thumbnail_path);
                }
                $path = $request->file('thumbnail')->store('lecture_videos/thumbnails', 'public');
                $data['thumbnail_path'] = Storage::url($path);
            }

            $lectureVideo->update($data);

            // 첨부파일 삭제
            if ($request->has('delete_attachments') && is_array($request->delete_attachments)) {
                foreach ($request->delete_attachments as $id) {
                    if (empty($id)) {
                        continue;
                    }
                    $att = LectureVideoAttachment::find($id);
                    if ($att && $att->lecture_video_id === (int) $lectureVideo->id) {
                        $this->deleteFile($att->path);
                        $att->delete();
                    }
                }
            }

            // 첨부파일 업로드
            if ($request->hasFile('attachments')) {
                $maxOrder = (int) $lectureVideo->attachments()->max('order');
                $order = $maxOrder + 1;
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('lecture_videos/attachments', 'public');
                    LectureVideoAttachment::create([
                        'lecture_video_id' => $lectureVideo->id,
                        'path' => Storage::url($path),
                        'name' => $file->getClientOriginalName(),
                        'order' => $order++,
                    ]);
                }
            }

            DB::commit();
            return $lectureVideo->fresh(['attachments']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('강의 영상 수정 실패', ['lecture_video_id' => $lectureVideo->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * 강의 영상을 삭제합니다.
     */
    public function delete(LectureVideo $lectureVideo): bool
    {
        DB::beginTransaction();
        try {
            // 썸네일 삭제
            if ($lectureVideo->thumbnail_path) {
                $this->deleteFile($lectureVideo->thumbnail_path);
            }

            // 첨부파일 삭제
            foreach ($lectureVideo->attachments as $att) {
                $this->deleteFile($att->path);
            }

            $lectureVideo->attachments()->delete();
            $lectureVideo->delete();

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('강의 영상 삭제 실패', ['lecture_video_id' => $lectureVideo->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * 파일을 삭제합니다.
     */
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
