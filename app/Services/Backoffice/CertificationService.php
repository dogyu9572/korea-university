<?php

namespace App\Services\Backoffice;

use App\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CertificationService
{
    /**
     * 자격증 목록을 조회합니다.
     */
    public function getList(Request $request)
    {
        $query = Certification::query();

        // 구분 검색
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // 접수상태 검색
        if ($request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }

        // 시험일 검색
        if ($request->filled('exam_date_start')) {
            $query->where('exam_date', '>=', $request->exam_date_start);
        }
        if ($request->filled('exam_date_end')) {
            $query->where('exam_date', '<=', $request->exam_date_end);
        }

        // 접수기간 검색
        if ($request->filled('application_start')) {
            $query->where('application_start', '>=', $request->application_start);
        }
        if ($request->filled('application_end')) {
            $query->where('application_end', '<=', $request->application_end);
        }

        // 자격증명 검색
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
     * 자격증을 생성합니다.
     */
    public function create(Request $request): Certification
    {
        DB::beginTransaction();
        try {
            $data = $request->only([
                'level',
                'name',
                'exam_date',
                'venue_category_ids',
                'exam_method',
                'passing_score',
                'eligibility',
                'exam_fee',
                'exam_overview',
                'exam_trend',
                'exam_venue',
                'is_public',
                'capacity',
                'capacity_unlimited',
                'application_status',
                'payment_methods',
                'deposit_account',
                'deposit_deadline_days',
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

            // venue_category_ids 배열 처리
            if ($request->has('venue_category_ids') && is_array($request->venue_category_ids)) {
                $data['venue_category_ids'] = array_filter($request->venue_category_ids);
            } else {
                $data['venue_category_ids'] = null;
            }

            // payment_methods 배열 처리
            if (!$request->has('payment_methods') || !is_array($request->payment_methods)) {
                $data['payment_methods'] = null;
            }

            // 썸네일 처리
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('certifications/thumbnails', 'public');
                $data['thumbnail_path'] = Storage::url($thumbnailPath);
            }

            // 자격증 생성
            $certification = Certification::create($data);

            DB::commit();
            return $certification;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('자격증 생성 실패', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * 자격증을 수정합니다.
     */
    public function update(Certification $certification, Request $request): Certification
    {
        DB::beginTransaction();
        try {
            $data = $request->only([
                'level',
                'name',
                'exam_date',
                'venue_category_ids',
                'exam_method',
                'passing_score',
                'eligibility',
                'exam_fee',
                'exam_overview',
                'exam_trend',
                'exam_venue',
                'is_public',
                'capacity',
                'capacity_unlimited',
                'application_status',
                'payment_methods',
                'deposit_account',
                'deposit_deadline_days',
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

            // venue_category_ids 배열 처리
            if ($request->has('venue_category_ids') && is_array($request->venue_category_ids)) {
                $data['venue_category_ids'] = array_filter($request->venue_category_ids);
            } else {
                $data['venue_category_ids'] = null;
            }

            // payment_methods 배열 처리
            if (!$request->has('payment_methods') || !is_array($request->payment_methods)) {
                $data['payment_methods'] = null;
            }

            // 썸네일 삭제 처리
            if ($request->has('delete_thumbnail') && $request->delete_thumbnail == '1') {
                if ($certification->thumbnail_path) {
                    $this->deleteFile($certification->thumbnail_path);
                }
                $data['thumbnail_path'] = null;
            }
            
            // 썸네일 업로드 처리
            if ($request->hasFile('thumbnail')) {
                // 기존 썸네일 삭제
                if ($certification->thumbnail_path) {
                    $this->deleteFile($certification->thumbnail_path);
                }
                $thumbnailPath = $request->file('thumbnail')->store('certifications/thumbnails', 'public');
                $data['thumbnail_path'] = Storage::url($thumbnailPath);
            }

            // 자격증 업데이트
            $certification->update($data);

            DB::commit();
            return $certification->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('자격증 수정 실패', ['error' => $e->getMessage(), 'certification_id' => $certification->id]);
            throw $e;
        }
    }

    /**
     * 자격증을 삭제합니다.
     */
    public function delete(Certification $certification): bool
    {
        DB::beginTransaction();
        try {
            // 썸네일 삭제
            if ($certification->thumbnail_path) {
                $this->deleteFile($certification->thumbnail_path);
            }

            // 자격증 삭제
            $certification->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('자격증 삭제 실패', ['error' => $e->getMessage(), 'certification_id' => $certification->id]);
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
