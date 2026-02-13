<?php

namespace App\Services\Backoffice;

use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BoardPostService
{
    /**
     * 동적 테이블명 생성
     */
    public function getTableName(string $slug): string
    {
        return 'board_' . $slug;
    }

    /**
     * 게시글 목록 조회
     * @param bool $forPublic 사용자 페이지용이면 true(삭제/비활성 제외)
     */
    public function getPosts(string $slug, Request $request, bool $forPublic = false)
    {
        $query = DB::table($this->getTableName($slug));

        if ($forPublic) {
            $query->whereNull('deleted_at')->where('is_active', 1);
        }

        $this->applySearchFilters($query, $request);
        
        // 목록 개수 설정
        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 15;
        
        // 정렬 기능이 활성화된 게시판인지 확인
        $board = Board::where('slug', $slug)->first();
        if ($board && $board->enable_sorting) {
            // 정렬 기능 활성화: sort_order 내림차순 (큰 값이 위), 공지글, 최신순
            $posts = $query->orderBy('sort_order', 'desc')
                ->orderBy('is_notice', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->withQueryString();
        } else {
            // 정렬 기능 비활성화: 공지글, 최신순
            $posts = $query->orderBy('is_notice', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->withQueryString();
        }

        $this->transformDates($posts);
        return $posts;
    }

    /**
     * 최신글 조회 (메인 등용)
     * @param bool $forPublic 사용자 페이지용이면 true(삭제/비활성 제외)
     */
    public function getLatestPosts(string $slug, int $limit = 4, bool $forPublic = true): \Illuminate\Support\Collection
    {
        $query = DB::table($this->getTableName($slug));

        if ($forPublic) {
            $query->whereNull('deleted_at')->where('is_active', 1);
        }

        $items = $query->orderBy('created_at', 'desc')->limit($limit)->get();

        return collect($items)->transform(function ($post) {
            foreach (['created_at', 'updated_at'] as $dateField) {
                if (isset($post->$dateField) && is_string($post->$dateField)) {
                    $post->$dateField = Carbon::parse($post->$dateField);
                }
            }
            return $post;
        });
    }

    /**
     * 검색 필터 적용
     */
    private function applySearchFilters($query, Request $request): void
    {
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('keyword')) {
            $this->applyKeywordSearch($query, $request->keyword, $request->search_type);
        }
    }

    /**
     * 키워드 검색 적용
     */
    private function applyKeywordSearch($query, string $keyword, ?string $searchType): void
    {
        if ($searchType === 'title') {
            $query->where('title', 'like', "%{$keyword}%");
        } elseif ($searchType === 'content') {
            $query->where('content', 'like', "%{$keyword}%");
        } else {
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('content', 'like', "%{$keyword}%");
            });
        }
    }

    /**
     * 날짜 변환
     */
    private function transformDates($posts): void
    {
        $posts->getCollection()->transform(function ($post) {
            foreach (['created_at', 'updated_at'] as $dateField) {
                if (isset($post->$dateField) && is_string($post->$dateField)) {
                    $post->$dateField = Carbon::parse($post->$dateField);
                }
            }
            return $post;
        });
    }

    /**
     * 게시글 저장
     */
    public function storePost(string $slug, array $validated, Request $request, $board): int
    {
        $data = $this->preparePostData($validated, $request, $slug, $board);
        
        return DB::table($this->getTableName($slug))->insertGetId($data);
    }

    /**
     * 게시글 데이터 준비
     */
    private function preparePostData(array $validated, Request $request, string $slug, $board): array
    {
        // 정렬 기능 활성화된 게시판인 경우 자동으로 sort_order 설정
        $sortOrder = 0;
        if ($board && $board->enable_sorting) {
            $sortOrder = $this->getNextSortOrder($slug);
        }
        
        // is_active 필드 처리: 필드가 활성화되어 있고 요청에 있으면 사용, 없으면 기본값 true
        $isActive = true;
        if ($board && $board->isFieldEnabled('is_active')) {
            $isActive = $request->has('is_active') ? (bool)$request->input('is_active') : true;
        }

        return [
            'user_id' => null,
            'author_name' => $validated['author_name'] ?? '관리자',
            'title' => $validated['title'],
            'content' => $this->sanitizeContent($validated['content']),
            'category' => $validated['category'] ?? null,
            'is_notice' => $request->has('is_notice'),
            'is_secret' => $request->has('is_secret'),
            'is_active' => $isActive,
            'password' => $validated['password'] ?? null,
            'thumbnail' => $this->handleThumbnail($request, $slug),
            'attachments' => json_encode($this->handleAttachments($request, $slug)),
            'custom_fields' => $this->getCustomFieldsJson($request, $board),
            'view_count' => 0,
            'sort_order' => $sortOrder,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    /**
     * 다음 정렬 순서 값을 계산합니다 (외부에서 호출 가능)
     */
    public function calculateNextSortOrder(string $slug): int
    {
        $maxSortOrder = DB::table($this->getTableName($slug))
            ->max('sort_order');
        
        return ($maxSortOrder ?? 0) + 1;
    }

    /**
     * 다음 정렬 순서 값을 가져옵니다 (정렬 기능 활성화된 게시판에만 사용)
     */
    private function getNextSortOrder(string $slug): int
    {
        return $this->calculateNextSortOrder($slug);
    }

    /**
     * HTML 내용 정리 (에디터 bold/italic/strikethrough 등 유지)
     * strip_tags는 태그명 대소문자 구분하므로, 먼저 태그를 소문자로 통일한 뒤 제거
     */
    private function sanitizeContent(string $content): string
    {
        $content = $this->normalizeHtmlTagCase($content);
        $allowedTags = '<p><br><strong><b><em><i><u><s><strike><ol><ul><li><h1><h2><h3><h4><h5><h6><blockquote><pre><code><table><thead><tbody><tr><td><th><a><img><div><span><iframe><video><source>';
        return strip_tags($content, $allowedTags);
    }

    /**
     * HTML 태그명을 소문자로 통일 (strip_tags 대소문자 구분 대응)
     */
    private function normalizeHtmlTagCase(string $content): string
    {
        return preg_replace_callback('/<\/?([a-zA-Z][a-zA-Z0-9]*)\b([^>]*)>/', function (array $m) {
            return strtolower($m[1]) === '!doctype' ? $m[0] : (str_starts_with($m[0], '</') ? '</' . strtolower($m[1]) . $m[2] . '>' : '<' . strtolower($m[1]) . $m[2] . '>');
        }, $content);
    }

    /**
     * 썸네일 처리
     */
    private function handleThumbnail(Request $request, string $slug): ?string
    {
        // 썸네일 제거 요청이 있는 경우
        if ($request->has('remove_thumbnail')) {
            return null;
        }
        
        // 새 썸네일이 업로드된 경우
        if ($request->hasFile('thumbnail')) {
            return $request->file('thumbnail')->store('thumbnails/' . $slug, 'public');
        }
        
        // 기존 썸네일이 있는 경우 보존
        if ($request->has('existing_thumbnail')) {
            return $request->input('existing_thumbnail');
        }
        
        return null;
    }

    /**
     * 첨부파일 처리
     */
    private function handleAttachments(Request $request, string $slug): array
    {
        $attachments = [];
        $removeIndices = $request->input('remove_attachments', []);
        
        // 기존 첨부파일 보존 (제거 요청이 없는 것만)
        if ($request->has('existing_attachments')) {
            $existingAttachments = $request->input('existing_attachments', []);
            foreach ($existingAttachments as $index => $attachment) {
                // 제거 요청이 있는 인덱스는 제외
                if (in_array($index, $removeIndices)) {
                    continue;
                }
                
                if (is_string($attachment)) {
                    $attachment = json_decode($attachment, true);
                }
                if (is_array($attachment) && isset($attachment['name'], $attachment['path'])) {
                    $attachments[] = $attachment;
                }
            }
        }
        
        // 새 첨부파일 추가
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $file->store('uploads/' . $slug, 'public'),
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
        }
        
        return $attachments;
    }

    /**
     * 커스텀 필드 JSON 생성
     */
    private function getCustomFieldsJson(Request $request, $board): ?string
    {
        $customFields = $this->processCustomFields($request, $board);
        return !empty($customFields) ? json_encode($customFields) : null;
    }

    /**
     * 커스텀 필드 처리
     */
    private function processCustomFields(Request $request, $board): array
    {
        if (!$board->custom_fields_config || !is_array($board->custom_fields_config)) {
            return [];
        }

        $customFields = [];
        foreach ($board->custom_fields_config as $fieldConfig) {
            $fieldName = $fieldConfig['name'];
            $customFields[$fieldName] = $request->input("custom_field_{$fieldName}");
        }
        return $customFields;
    }

    /**
     * 게시글 조회
     * @param bool $forPublic 사용자 페이지용이면 true(삭제/비활성 제외)
     */
    public function getPost(string $slug, int $postId, bool $forPublic = false)
    {
        $query = DB::table($this->getTableName($slug))->where('id', $postId);
        if ($forPublic) {
            $query->whereNull('deleted_at')->where('is_active', 1);
        }
        $post = $query->first();

        if (!$post) {
            return null;
        }

        $this->transformSinglePostDates($post);
        return $post;
    }

    /**
     * 이전 글 조회 (목록에서 현재 글 위에 있는 글, 공지·최신순 기준)
     * @param object $currentPost 현재 글 (id, is_notice, created_at 포함)
     */
    public function getPrevPost(string $slug, $currentPost, bool $forPublic = false)
    {
        $query = DB::table($this->getTableName($slug))
            ->where(function ($q) use ($currentPost) {
                $q->where('is_notice', '>', $currentPost->is_notice ?? 0)
                    ->orWhere(function ($q2) use ($currentPost) {
                        $q2->where('is_notice', '=', $currentPost->is_notice ?? 0)
                            ->where('created_at', '>', $currentPost->created_at);
                    });
            })
            ->orderBy('is_notice', 'asc')
            ->orderBy('created_at', 'asc')
            ->limit(1);
        if ($forPublic) {
            $query->whereNull('deleted_at')->where('is_active', 1);
        }
        $post = $query->first();
        if ($post) {
            $this->transformSinglePostDates($post);
        }
        return $post;
    }

    /**
     * 다음 글 조회 (목록에서 현재 글 아래에 있는 글, 공지·최신순 기준)
     * @param object $currentPost 현재 글 (id, is_notice, created_at 포함)
     */
    public function getNextPost(string $slug, $currentPost, bool $forPublic = false)
    {
        $query = DB::table($this->getTableName($slug))
            ->where(function ($q) use ($currentPost) {
                $q->where('is_notice', '<', $currentPost->is_notice ?? 0)
                    ->orWhere(function ($q2) use ($currentPost) {
                        $q2->where('is_notice', '=', $currentPost->is_notice ?? 0)
                            ->where('created_at', '<', $currentPost->created_at);
                    });
            })
            ->orderBy('is_notice', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(1);
        if ($forPublic) {
            $query->whereNull('deleted_at')->where('is_active', 1);
        }
        $post = $query->first();
        if ($post) {
            $this->transformSinglePostDates($post);
        }
        return $post;
    }

    /**
     * 단일 게시글 날짜 변환
     */
    private function transformSinglePostDates($post): void
    {
        foreach (['created_at', 'updated_at'] as $dateField) {
            if (isset($post->$dateField) && is_string($post->$dateField)) {
                $post->$dateField = Carbon::parse($post->$dateField);
            }
        }
    }

    /**
     * 게시글 수정
     */
    public function updatePost(string $slug, int $postId, array $validated, Request $request, $board): bool
    {
        // 기존 게시물 조회
        $existingPost = $this->getPost($slug, $postId);
        
        $data = $this->prepareUpdateData($validated, $request, $slug, $board, $existingPost);
        
        return DB::table($this->getTableName($slug))
            ->where('id', $postId)
            ->update($data);
    }

    /**
     * 수정 데이터 준비
     */
    private function prepareUpdateData(array $validated, Request $request, string $slug, $board, $existingPost = null): array
    {
        // is_active 필드 처리
        // 필드가 활성화되어 있고 요청에 있으면 사용
        // 필드가 비활성화되어 있으면 기존 값 유지 (수정 시)
        // 필드가 활성화되어 있지만 요청에 없으면 기본값 true
        $isActive = true;
        if ($board && $board->isFieldEnabled('is_active')) {
            $isActive = $request->has('is_active') ? (bool)$request->input('is_active') : true;
        } elseif ($existingPost && isset($existingPost->is_active)) {
            // 필드가 비활성화되어 있으면 기존 값 유지
            $isActive = (bool)$existingPost->is_active;
        }

        return [
            'title' => $validated['title'],
            'content' => $this->sanitizeContent($validated['content']),
            'category' => $validated['category'] ?? null,
            'is_notice' => $request->has('is_notice'),
            'is_secret' => $request->has('is_secret'),
            'is_active' => $isActive,
            'author_name' => $validated['author_name'] ?? null,
            'password' => $validated['password'] ?? null,
            'thumbnail' => $this->handleThumbnail($request, $slug),
            'attachments' => json_encode($this->handleAttachments($request, $slug)),
            'custom_fields' => $this->getCustomFieldsJson($request, $board),
            'sort_order' => $request->input('sort_order', 0),
            'updated_at' => now()
        ];
    }

    /**
     * 게시글 삭제
     */
    public function deletePost(string $slug, int $postId): bool
    {
        $post = DB::table($this->getTableName($slug))->where('id', $postId)->first();
        
        if (!$post) {
            return false;
        }

        $this->deleteAttachments($post);
        
        return DB::table($this->getTableName($slug))->where('id', $postId)->delete();
    }

    /**
     * 첨부파일 삭제
     */
    private function deleteAttachments($post): void
    {
        if (!$post->attachments) {
            return;
        }

        $attachments = json_decode($post->attachments, true);
        if (!is_array($attachments)) {
            return;
        }

        foreach ($attachments as $attachment) {
            if (isset($attachment['path'])) {
                $filePath = storage_path('app/public/' . $attachment['path']);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }
    }

    /**
     * 일괄 삭제
     */
    public function bulkDelete(string $slug, array $postIds): int
    {
        return DB::table($this->getTableName($slug))->whereIn('id', $postIds)->delete();
    }
}
