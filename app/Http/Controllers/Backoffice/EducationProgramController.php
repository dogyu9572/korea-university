<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Requests\Backoffice\EducationProgramStoreRequest;
use App\Http\Requests\Backoffice\EducationProgramUpdateRequest;
use App\Services\Backoffice\EducationProgramService;
use App\Models\EducationProgram;
use Illuminate\Http\Request;

class EducationProgramController extends BaseController
{
    protected EducationProgramService $educationProgramService;

    public function __construct(EducationProgramService $educationProgramService)
    {
        $this->educationProgramService = $educationProgramService;
    }

    /**
     * 교육 프로그램 목록을 표시합니다.
     */
    public function index(Request $request)
    {
        $programs = $this->educationProgramService->getList($request);
        return $this->view('backoffice.education-programs.index', compact('programs'));
    }

    /**
     * 교육 프로그램 등록 폼을 표시합니다.
     */
    public function create()
    {
        $formData = $this->educationProgramService->getFormData(null);
        return $this->view('backoffice.education-programs.create', $formData);
    }

    /**
     * 교육 프로그램을 저장합니다.
     */
    public function store(EducationProgramStoreRequest $request)
    {
        try {
            $this->educationProgramService->create($request);
            return redirect()->route('backoffice.education-programs.index')
                ->with('success', '교육 프로그램이 등록되었습니다.');
        } catch (\Exception $e) {
            return redirect()->route('backoffice.education-programs.create')
                ->with('error', '등록 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * 교육 프로그램 수정 폼을 표시합니다.
     */
    public function edit(EducationProgram $educationProgram)
    {
        $educationProgram->load(['schedules', 'attachments']);
        $formData = $this->educationProgramService->getFormData($educationProgram);
        return $this->view('backoffice.education-programs.edit', array_merge(compact('educationProgram'), $formData));
    }

    /**
     * 교육 프로그램을 수정합니다.
     */
    public function update(EducationProgramUpdateRequest $request, EducationProgram $educationProgram)
    {
        try {
            $this->educationProgramService->update($educationProgram, $request);
            return redirect()->route('backoffice.education-programs.index')
                ->with('success', '교육 프로그램이 수정되었습니다.');
        } catch (\Exception $e) {
            return redirect()->route('backoffice.education-programs.edit', $educationProgram)
                ->with('error', '수정 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * 교육 프로그램을 삭제합니다.
     */
    public function destroy(EducationProgram $educationProgram)
    {
        try {
            $this->educationProgramService->delete($educationProgram);
            return redirect()->route('backoffice.education-programs.index')
                ->with('success', '교육 프로그램이 삭제되었습니다.');
        } catch (\Exception $e) {
            return redirect()->route('backoffice.education-programs.index')
                ->with('error', '삭제 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }
}
