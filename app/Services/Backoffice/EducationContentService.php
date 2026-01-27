<?php

namespace App\Services\Backoffice;

use App\Models\EducationContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class EducationContentService
{
    /**
     * 교육 콘텐츠를 조회합니다. 없으면 빈 모델을 반환합니다.
     */
    public function getContents(): EducationContent
    {
        try {
            if (!Schema::hasTable('education_contents')) {
                return new EducationContent();
            }

            return EducationContent::first() ?: new EducationContent();
        } catch (QueryException $e) {
            Log::error('교육 콘텐츠 조회 실패', ['error' => $e->getMessage()]);
            return new EducationContent();
        }
    }

    /**
     * 교육 콘텐츠를 저장합니다.
     */
    public function updateContents(Request $request): bool
    {
        try {
            if (!Schema::hasTable('education_contents')) {
                return false;
            }

            $data = $request->only([
                'education_guide',
                'certification_guide',
                'expert_level_1',
                'expert_level_2',
                'seminar_guide',
                'overseas_training_guide',
            ]);

            EducationContent::updateOrCreateContents($data);

            return true;
        } catch (\Exception $e) {
            Log::error('교육 콘텐츠 저장 실패', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
