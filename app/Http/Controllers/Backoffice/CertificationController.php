<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Requests\Backoffice\CertificationStoreRequest;
use App\Http\Requests\Backoffice\CertificationUpdateRequest;
use App\Services\Backoffice\CertificationService;
use App\Services\Backoffice\CategoryService;
use App\Models\Certification;
use App\Models\Category;
use Illuminate\Http\Request;

class CertificationController extends BaseController
{
    protected CertificationService $certificationService;
    protected CategoryService $categoryService;

    public function __construct(CertificationService $certificationService, CategoryService $categoryService)
    {
        $this->certificationService = $certificationService;
        $this->categoryService = $categoryService;
    }

    /**
     * 자격증 목록을 표시합니다.
     */
    public function index(Request $request)
    {
        $certifications = $this->certificationService->getList($request);
        return $this->view('backoffice.certifications.index', compact('certifications'));
    }

    /**
     * 자격증 등록 폼을 표시합니다.
     */
    public function create()
    {
        $venueCategories = $this->getVenueCategories();
        return $this->view('backoffice.certifications.create', compact('venueCategories'));
    }

    /**
     * 자격증을 저장합니다.
     */
    public function store(CertificationStoreRequest $request)
    {
        try {
            $this->certificationService->create($request);
            return redirect()->route('backoffice.certifications.index')
                ->with('success', '자격증이 등록되었습니다.');
        } catch (\Exception $e) {
            return redirect()->route('backoffice.certifications.create')
                ->with('error', '등록 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * 자격증 수정 폼을 표시합니다.
     */
    public function edit(Certification $certification)
    {
        $venueCategories = $this->getVenueCategories();
        return $this->view('backoffice.certifications.edit', compact('certification', 'venueCategories'));
    }

    /**
     * 자격증을 수정합니다.
     */
    public function update(CertificationUpdateRequest $request, Certification $certification)
    {
        try {
            $this->certificationService->update($certification, $request);
            return redirect()->route('backoffice.certifications.index')
                ->with('success', '자격증이 수정되었습니다.');
        } catch (\Exception $e) {
            return redirect()->route('backoffice.certifications.edit', $certification)
                ->with('error', '수정 중 오류가 발생했습니다: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * 자격증을 삭제합니다.
     */
    public function destroy(Certification $certification)
    {
        try {
            $this->certificationService->delete($certification);
            return redirect()->route('backoffice.certifications.index')
                ->with('success', '자격증이 삭제되었습니다.');
        } catch (\Exception $e) {
            return redirect()->route('backoffice.certifications.index')
                ->with('error', '삭제 중 오류가 발생했습니다: ' . $e->getMessage());
        }
    }

    /**
     * 시험장 그룹의 1차 카테고리를 조회합니다.
     */
    private function getVenueCategories()
    {
        $venueGroup = Category::where('name', '시험장')
            ->where('depth', 0)
            ->whereNull('parent_id')
            ->first();

        if (!$venueGroup) {
            return collect([]);
        }

        return $this->categoryService->getFirstLevelCategoriesByGroup($venueGroup->id);
    }
}
