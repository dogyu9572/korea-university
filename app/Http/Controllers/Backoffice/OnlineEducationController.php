<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Requests\Backoffice\OnlineEducationStoreRequest;
use App\Http\Requests\Backoffice\OnlineEducationUpdateRequest;
use App\Services\Backoffice\OnlineEducationService;
use App\Models\OnlineEducation;
use Illuminate\Http\Request;

class OnlineEducationController extends BaseController
{
    protected OnlineEducationService $onlineEducationService;

    public function __construct(OnlineEducationService $onlineEducationService)
    {
        $this->onlineEducationService = $onlineEducationService;
    }

    /**
     * 온라인 교육 목록을 표시합니다.
     */
    public function index(Request $request)
    {
        $programs = $this->onlineEducationService->getList($request);
        return $this->view('backoffice.online-educations.index', compact('programs'));
    }

    /**
     * 온라인 교육 등록 폼을 표시합니다.
     */
    public function create()
    {
        $formData = $this->onlineEducationService->getFormData(null);
        return $this->view('backoffice.online-educations.create', $formData);
    }

    /**
     * 온라인 교육을 저장합니다.
     */
    public function store(OnlineEducationStoreRequest $request)
    {
        try {
            $this->onlineEducationService->create($request);
            return redirect()->route('backoffice.online-educations.index')
                ->with('success', '온라인 교육이 등록되었습니다.');
        } catch (\Exception $e) {
            return redirect()->route('backoffice.online-educations.create')
                ->with('error', '등록 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * 온라인 교육 수정 폼을 표시합니다.
     */
    public function edit(OnlineEducation $onlineEducation)
    {
        $onlineEducation->load(['lectures', 'attachments']);
        $formData = $this->onlineEducationService->getFormData($onlineEducation);
        return $this->view('backoffice.online-educations.edit', array_merge(compact('onlineEducation'), $formData));
    }

    /**
     * 온라인 교육을 수정합니다.
     */
    public function update(OnlineEducationUpdateRequest $request, OnlineEducation $onlineEducation)
    {
        try {
            $this->onlineEducationService->update($onlineEducation, $request);
            return redirect()->route('backoffice.online-educations.index')
                ->with('success', '온라인 교육이 수정되었습니다.');
        } catch (\Exception $e) {
            return redirect()->route('backoffice.online-educations.edit', $onlineEducation)
                ->with('error', '수정 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * 온라인 교육을 삭제합니다.
     */
    public function destroy(OnlineEducation $onlineEducation)
    {
        try {
            $this->onlineEducationService->delete($onlineEducation);
            return redirect()->route('backoffice.online-educations.index')
                ->with('success', '온라인 교육이 삭제되었습니다.');
        } catch (\Exception $e) {
            return redirect()->route('backoffice.online-educations.index')
                ->with('error', '삭제 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    /**
     * 강의영상 검색 API
     */
    public function searchLectures(Request $request)
    {
        $lectures = $this->onlineEducationService->searchLectures($request);
        return response()->json($lectures);
    }

    /**
     * 강의 삭제 API
     */
    public function deleteLecture(int $id)
    {
        try {
            $this->onlineEducationService->deleteLecture($id);
            return response()->json(['success' => true, 'message' => '강의가 삭제되었습니다.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => '삭제 중 오류가 발생했습니다: ' . $e->getMessage()], 500);
        }
    }
}
