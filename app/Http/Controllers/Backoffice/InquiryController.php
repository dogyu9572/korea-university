<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Requests\Backoffice\InquiryReplyRequest;
use App\Services\Backoffice\InquiryService;
use Illuminate\Http\Request;

class InquiryController extends BaseController
{
    protected $inquiryService;

    public function __construct(InquiryService $inquiryService)
    {
        $this->inquiryService = $inquiryService;
    }

    /**
     * 문의 목록 페이지
     */
    public function index(Request $request)
    {
        $filters = [
            'category' => $request->get('category', '전체'),
            'status' => $request->get('status', '전체'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'search_type' => $request->get('search_type', '전체'),
            'search_term' => $request->get('search_term', ''),
        ];

        $perPage = $request->get('per_page', 20);
        $inquiries = $this->inquiryService->getInquiries($filters, $perPage);

        // 분류 목록
        $categories = $this->inquiryService->getCategories();
        $categories = array_merge(['전체' => '전체'], array_combine($categories, $categories));

        return $this->view('backoffice.inquiries.index', compact('inquiries', 'filters', 'categories', 'perPage'));
    }

    /**
     * 문의 상세 페이지
     */
    public function show(int $id)
    {
        $inquiry = $this->inquiryService->getInquiry($id);
        $reply = $this->inquiryService->getReply($id);

        return $this->view('backoffice.inquiries.show', compact('inquiry', 'reply'));
    }

    /**
     * 답변 저장/수정
     */
    public function updateReply(InquiryReplyRequest $request, int $id)
    {
        try {
            $data = $request->validated();
            $data['admin_id'] = auth()->id();
            
            // 삭제할 파일 ID 추가
            if ($request->has('delete_reply_files')) {
                $data['delete_reply_files'] = $request->input('delete_reply_files', []);
            }

            // 파일 처리
            $files = null;
            
            // 모든 파일 정보 로깅
            $allRequestData = $request->all();
            $allFiles = $request->allFiles();
            
            \Log::info('파일 업로드 요청 확인', [
                'has_attachments_key' => $request->hasFile('attachments'),
                'all_files_keys' => array_keys($allFiles),
                'request_all_keys' => array_keys($allRequestData),
                'request_attachments_direct' => $request->file('attachments') !== null ? 'exists' : 'null',
                'request_method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'content_length' => $request->header('Content-Length'),
                '_FILES' => $_FILES,  // PHP 전역 변수 직접 확인
                'php_upload_max_filesize' => ini_get('upload_max_filesize'),
                'php_post_max_size' => ini_get('post_max_size'),
                'inquiry_id' => $id
            ]);
            
            // 파일 확인 (다른 게시판과 동일한 방식)
            $uploadedFiles = $request->file('attachments');
            
            if ($uploadedFiles) {
                // 단일 파일이면 배열로 변환
                if (!is_array($uploadedFiles)) {
                    $uploadedFiles = [$uploadedFiles];
                }
                $files = $uploadedFiles;
                
                \Log::info('답변 파일 업로드 시도', [
                    'file_count' => count($files),
                    'inquiry_id' => $id,
                    'file_names' => array_map(function($f) { 
                        return $f ? $f->getClientOriginalName() : 'null'; 
                    }, $files)
                ]);
            } else {
                \Log::info('파일이 전송되지 않음', [
                    'inquiry_id' => $id,
                    'has_attachments' => $request->hasFile('attachments'),
                    'attachments_direct' => $request->file('attachments'),
                    'all_files' => $allFiles
                ]);
            }

            $this->inquiryService->createOrUpdateReply($id, $data, $files);

            return redirect()->route('backoffice.inquiries.index')
                ->with('success', '답변이 저장되었습니다.');
        } catch (\Exception $e) {
            \Log::error('답변 저장 실패', [
                'inquiry_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => '답변 저장 중 오류가 발생했습니다. 다시 시도해주세요.']);
        }
    }

    /**
     * 문의 삭제
     */
    public function destroy(int $id)
    {
        $this->inquiryService->deleteInquiry($id);

        return redirect()->route('backoffice.inquiries.index')
            ->with('success', '문의가 삭제되었습니다.');
    }

    /**
     * 문의 일괄 삭제
     */
    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => '선택된 문의가 없습니다.'], 400);
        }

        $this->inquiryService->deleteInquiries($ids);

        return response()->json(['success' => true, 'message' => '선택된 문의가 삭제되었습니다.']);
    }
}
