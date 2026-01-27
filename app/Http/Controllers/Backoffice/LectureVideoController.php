<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Requests\Backoffice\LectureVideoStoreRequest;
use App\Http\Requests\Backoffice\LectureVideoUpdateRequest;
use App\Services\Backoffice\LectureVideoService;
use App\Models\LectureVideo;
use Illuminate\Http\Request;

class LectureVideoController extends BaseController
{
    public function __construct(
        protected LectureVideoService $lectureVideoService
    ) {}

    public function index(Request $request)
    {
        $lectureVideos = $this->lectureVideoService->getList($request);
        return $this->view('backoffice.lecture-videos.index', compact('lectureVideos'));
    }

    public function create()
    {
        return $this->view('backoffice.lecture-videos.create');
    }

    public function store(LectureVideoStoreRequest $request)
    {
        try {
            $this->lectureVideoService->create($request);
            return redirect()->route('backoffice.lecture-videos.index')
                ->with('success', '강의 영상이 등록되었습니다.');
        } catch (\Throwable $e) {
            return redirect()->route('backoffice.lecture-videos.create')
                ->with('error', '등록 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(LectureVideo $lectureVideo)
    {
        $lectureVideo->load(['attachments']);
        return $this->view('backoffice.lecture-videos.edit', compact('lectureVideo'));
    }

    public function update(LectureVideoUpdateRequest $request, LectureVideo $lectureVideo)
    {
        try {
            $this->lectureVideoService->update($lectureVideo, $request);
            return redirect()->route('backoffice.lecture-videos.index')
                ->with('success', '강의 영상이 수정되었습니다.');
        } catch (\Throwable $e) {
            return redirect()->route('backoffice.lecture-videos.edit', $lectureVideo)
                ->with('error', '수정 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(LectureVideo $lectureVideo)
    {
        try {
            $this->lectureVideoService->delete($lectureVideo);
            return redirect()->route('backoffice.lecture-videos.index')
                ->with('success', '강의 영상이 삭제되었습니다.');
        } catch (\Throwable $e) {
            return redirect()->route('backoffice.lecture-videos.index')
                ->with('error', '삭제 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }
}
