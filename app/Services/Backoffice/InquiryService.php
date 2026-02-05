<?php

namespace App\Services\Backoffice;

use App\Models\Inquiry;
use App\Models\InquiryReply;
use App\Models\InquiryFile;
use App\Models\InquiryReplyFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InquiryService
{
    /**
     * 분류 목록 반환
     */
    public function getCategories(): array
    {
        return [
            '교육',
            '자격증',
            '세미나',
            '해외연수',
            '기타',
        ];
    }

    /**
     * 문의 목록 조회 (검색/필터링/페이지네이션)
     */
    public function getInquiries(array $filters = [], int $perPage = 20)
    {
        $query = Inquiry::with(['member', 'reply']);

        // 분류 필터
        if (isset($filters['category']) && $filters['category']) {
            $query->byCategory($filters['category']);
        }

        // 답변 상태 필터
        if (isset($filters['status']) && $filters['status']) {
            $query->byStatus($filters['status']);
        }

        // 등록일 범위 필터
        if (isset($filters['start_date']) || isset($filters['end_date'])) {
            $query->byDateRange($filters['start_date'] ?? null, $filters['end_date'] ?? null);
        }

        // 검색어 필터
        if (isset($filters['search_type']) && isset($filters['search_term'])) {
            $query->search($filters['search_type'], $filters['search_term']);
        }

        // 정렬 (최신순)
        $query->orderBy('created_at', 'desc');

        // 페이지네이션
        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 문의 상세 조회 (조회수 증가 포함)
     */
    public function getInquiry(int $id)
    {
        $inquiry = Inquiry::with(['member', 'files', 'reply.files', 'reply.admin'])
            ->findOrFail($id);
        
        // 조회수 증가
        $inquiry->incrementViews();
        
        return $inquiry;
    }

    /**
     * 답변 조회
     */
    public function getReply(int $inquiryId)
    {
        return InquiryReply::where('inquiry_id', $inquiryId)
            ->with('files')
            ->first();
    }

    /**
     * 답변 생성/수정
     */
    public function createOrUpdateReply(int $inquiryId, array $data, $files = null)
    {
        try {
            return DB::transaction(function () use ($inquiryId, $data, $files) {
                $inquiry = Inquiry::findOrFail($inquiryId);
                
                // 기존 답변이 있는지 확인
                $reply = InquiryReply::where('inquiry_id', $inquiryId)->first();
                
                if ($reply) {
                    // 기존 답변 수정
                    // content가 비어있거나 빈 HTML만 있으면 기존 내용 유지
                    $content = $data['content'] ?? '';
                    $cleanContent = trim(strip_tags($content));
                    $cleanContent = preg_replace('/&nbsp;/i', ' ', $cleanContent);
                    $cleanContent = trim($cleanContent);
                    
                    if (empty($cleanContent)) {
                        // 기존 내용 유지
                        unset($data['content']);
                        Log::info('기존 답변 내용 유지 (빈 내용으로 인한)', [
                            'reply_id' => $reply->id,
                            'existing_content_length' => strlen($reply->content ?? '')
                        ]);
                    }
                    $reply->update($data);
                } else {
                    // 새 답변 생성 시 content는 필수
                    $data['inquiry_id'] = $inquiryId;
                    $reply = InquiryReply::create($data);
                }

                // 파일 삭제 처리
                if (isset($data['delete_reply_files']) && is_array($data['delete_reply_files'])) {
                    foreach ($data['delete_reply_files'] as $fileId) {
                        if (empty($fileId)) {
                            continue;
                        }
                        $file = InquiryReplyFile::find($fileId);
                        if ($file && $file->inquiry_reply_id == $reply->id) {
                            Storage::disk('public')->delete($file->file_path);
                            $file->delete();
                        }
                    }
                }

                // 파일 처리
                if ($files) {
                    if (!is_array($files)) {
                        $files = [$files];
                    }
                    Log::info('답변 파일 처리 시작', [
                        'reply_id' => $reply->id,
                        'file_count' => count($files)
                    ]);
                    
                    try {
                        $this->handleReplyFiles($reply->id, $files);
                    } catch (\Exception $e) {
                        Log::error('답변 파일 업로드 실패', [
                            'reply_id' => $reply->id,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        throw $e; // 트랜잭션 롤백을 위해 예외 재발생
                    }
                }

                // 문의 상태 업데이트
                $inquiry->update(['status' => $data['status']]);

                return $reply->load('files');
            });
        } catch (\Exception $e) {
            Log::error('답변 생성/수정 실패', [
                'inquiry_id' => $inquiryId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * 답변 파일 처리
     */
    private function handleReplyFiles(int $replyId, array $files)
    {
        Log::info('답변 파일 처리 시작', [
            'reply_id' => $replyId,
            'file_count' => count($files),
            'files_info' => array_map(function($f, $index) {
                if (!$f) {
                    return ['index' => $index, 'status' => 'null'];
                }
                return [
                    'index' => $index,
                    'name' => $f->getClientOriginalName(),
                    'size' => $f->getSize(),
                    'mime' => $f->getMimeType(),
                    'valid' => $f->isValid(),
                    'error' => $f->getError(),
                    'error_message' => $f->getErrorMessage()
                ];
            }, $files, array_keys($files))
        ]);
        
        // 최대 3개 파일 제한
        $fileCount = 0;
        $maxFiles = 3;
        
        foreach ($files as $index => $file) {
            if (!$file) {
                Log::warning('파일이 null입니다', ['index' => $index, 'reply_id' => $replyId]);
                continue;
            }
            
            if (!$file->isValid()) {
                Log::error('파일이 유효하지 않습니다', [
                    'index' => $index,
                    'reply_id' => $replyId,
                    'name' => $file->getClientOriginalName(),
                    'error' => $file->getError(),
                    'error_message' => $file->getErrorMessage()
                ]);
                continue;
            }
            
            if ($fileCount >= $maxFiles) {
                Log::warning('최대 파일 개수 초과', [
                    'reply_id' => $replyId,
                    'file_count' => $fileCount,
                    'max' => $maxFiles
                ]);
                break;
            }
            
            try {
                $path = $file->store('inquiry-replies', 'public');
                $fileName = $file->getClientOriginalName();
                $fileSize = $file->getSize();

                Log::info('답변 파일 저장 성공', [
                    'reply_id' => $replyId,
                    'file_name' => $fileName,
                    'file_path' => $path,
                    'file_size' => $fileSize
                ]);

                InquiryReplyFile::create([
                    'inquiry_reply_id' => $replyId,
                    'file_path' => $path,
                    'file_name' => $fileName,
                    'file_size' => $fileSize,
                ]);
                
                $fileCount++;
            } catch (\Exception $e) {
                Log::error('답변 파일 업로드 실패', [
                    'reply_id' => $replyId,
                    'file_name' => $file->getClientOriginalName(),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e; // 트랜잭션 롤백을 위해 예외 재발생
            }
        }
        
        Log::info('답변 파일 처리 완료', [
            'reply_id' => $replyId,
            'saved_count' => $fileCount,
            'total_count' => count($files)
        ]);
    }

    /**
     * 답변 삭제
     */
    public function deleteReply(int $replyId)
    {
        return DB::transaction(function () use ($replyId) {
            $reply = InquiryReply::with('files')->findOrFail($replyId);
            $inquiryId = $reply->inquiry_id;

            // 파일 삭제
            foreach ($reply->files as $file) {
                Storage::disk('public')->delete($file->file_path);
                $file->delete();
            }

            // 답변 삭제
            $reply->delete();

            // 문의 상태를 답변대기로 변경
            Inquiry::where('id', $inquiryId)->update(['status' => '답변대기']);

            return true;
        });
    }

    /**
     * 문의 일괄 삭제
     */
    public function deleteInquiries(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            $inquiries = Inquiry::with(['files', 'reply.files'])->whereIn('id', $ids)->get();

            foreach ($inquiries as $inquiry) {
                // 문의 첨부파일 삭제
                foreach ($inquiry->files as $file) {
                    Storage::disk('public')->delete($file->file_path);
                    $file->delete();
                }

                // 답변 및 답변 첨부파일 삭제
                if ($inquiry->reply) {
                    foreach ($inquiry->reply->files as $file) {
                        Storage::disk('public')->delete($file->file_path);
                        $file->delete();
                    }
                    $inquiry->reply->delete();
                }

                // 문의 삭제
                $inquiry->delete();
            }

            return true;
        });
    }

    /**
     * 문의 삭제
     */
    public function deleteInquiry(int $id)
    {
        return $this->deleteInquiries([$id]);
    }

    /**
     * 회원별 문의 목록 조회 (마이페이지)
     */
    public function getInquiriesByMember(int $memberId, array $filters = [], int $perPage = 20)
    {
        $query = Inquiry::where('member_id', $memberId)->with('reply');

        if (isset($filters['category']) && $filters['category'] && $filters['category'] !== '전체') {
            $query->byCategory($filters['category']);
        }

        if (isset($filters['status']) && $filters['status'] && $filters['status'] !== '전체') {
            $query->byStatus($filters['status']);
        }

        if (isset($filters['start_date']) || isset($filters['end_date'])) {
            $query->byDateRange($filters['start_date'] ?? null, $filters['end_date'] ?? null);
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 회원 본인 문의 상세 조회 (마이페이지, 조회수 증가)
     */
    public function getInquiryForMember(int $id, int $memberId)
    {
        $inquiry = Inquiry::where('id', $id)
            ->where('member_id', $memberId)
            ->with(['files', 'reply.files'])
            ->firstOrFail();

        $inquiry->incrementViews();

        return $inquiry;
    }

    /**
     * 이전/다음 문의 조회 (마이페이지 상세 하단용)
     */
    public function getPrevNextForMember(int $inquiryId, int $memberId): array
    {
        $current = Inquiry::where('member_id', $memberId)->find($inquiryId);
        if (!$current) {
            return ['prev' => null, 'next' => null];
        }

        $prev = Inquiry::where('member_id', $memberId)
            ->where('created_at', '>', $current->created_at)
            ->orderBy('created_at', 'asc')
            ->first();

        $next = Inquiry::where('member_id', $memberId)
            ->where('created_at', '<', $current->created_at)
            ->orderBy('created_at', 'desc')
            ->first();

        return ['prev' => $prev, 'next' => $next];
    }

    /**
     * 문의 등록 (마이페이지, 첨부파일 최대 3개·10MB)
     */
    public function createInquiry(int $memberId, array $data, $files = null)
    {
        return DB::transaction(function () use ($memberId, $data, $files) {
            $inquiry = Inquiry::create([
                'member_id' => $memberId,
                'category' => $data['category'],
                'title' => $data['title'],
                'content' => $data['content'],
                'status' => '답변대기',
            ]);

            if ($files && is_array($files)) {
                $count = 0;
                foreach ($files as $file) {
                    if ($count >= 3) {
                        break;
                    }
                    if (!$file || !$file->isValid()) {
                        continue;
                    }
                    if ($file->getSize() > 10 * 1024 * 1024) {
                        continue;
                    }
                    $path = $file->store('inquiries', 'public');
                    InquiryFile::create([
                        'inquiry_id' => $inquiry->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                    ]);
                    $count++;
                }
            } elseif ($files && is_object($files) && $files->isValid()) {
                if ($files->getSize() <= 10 * 1024 * 1024) {
                    $path = $files->store('inquiries', 'public');
                    InquiryFile::create([
                        'inquiry_id' => $inquiry->id,
                        'file_path' => $path,
                        'file_name' => $files->getClientOriginalName(),
                        'file_size' => $files->getSize(),
                    ]);
                }
            }

            return $inquiry;
        });
    }
}
