<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Requests\Backoffice\EducationContentRequest;
use App\Services\Backoffice\EducationContentService;

class EducationController extends BaseController
{
    protected EducationContentService $educationContentService;

    public function __construct(EducationContentService $educationContentService)
    {
        $this->educationContentService = $educationContentService;
    }

    /**
     * 교육 안내 관리 페이지를 표시합니다.
     */
    public function index()
    {
        $contents = $this->educationContentService->getContents();
        return $this->view('backoffice.education.index', compact('contents'));
    }

    /**
     * 교육 콘텐츠를 저장합니다.
     */
    public function update(EducationContentRequest $request)
    {
        $success = $this->educationContentService->updateContents($request);

        if ($success) {
            return redirect()->route('backoffice.education.index')
                ->with('success', '교육 안내가 저장되었습니다.');
        }

        return redirect()->route('backoffice.education.index')
            ->with('error', '저장하는 중 오류가 발생했습니다.')
            ->withInput();
    }
}
