<?php

namespace App\Services;

use App\Models\Certification;
use App\Models\Education;
use App\Models\OnlineEducation;
use App\Models\SeminarTraining;
use App\Services\Backoffice\BoardPostService;
use Illuminate\Support\Collection;

class HomeService
{
    private const SLIDE_LIMIT_PER_TYPE = 2;

    private const DEFAULT_IMAGE = '/images/img_mc01_sample.jpg';

    public function __construct(private BoardPostService $boardPostService)
    {
    }

    /**
     * 메인 교육·자격증 슬라이드용: 등록된 교육/자격증/온라인교육 프로그램을 조회해 공통 형식으로 반환
     */
    public function getEducationSlidesForMain(): Collection
    {
        $slides = collect();

        $educations = Education::where('is_public', true)
            ->orderBy('period_start', 'desc')
            ->limit(self::SLIDE_LIMIT_PER_TYPE)
            ->get();
        foreach ($educations as $item) {
            $slides->push((object) [
                'type' => 'education',
                'type_class' => 'c1',
                'type_label' => '교육',
                'title' => $item->name,
                'image_url' => $this->resolveThumbnailUrl($item->thumbnail_path),
                'url' => route('education_certification.application_ec_view', $item->id),
                'info_label_1' => '신청기간',
                'info_value_1' => $this->formatShortPeriod($item->application_start, $item->application_end),
                'info_label_2' => '교육기간',
                'info_value_2' => $this->formatShortPeriod($item->period_start, $item->period_end),
            ]);
        }

        $certifications = Certification::where('is_public', true)
            ->orderBy('exam_date', 'desc')
            ->limit(self::SLIDE_LIMIT_PER_TYPE)
            ->get();
        foreach ($certifications as $item) {
            $slides->push((object) [
                'type' => 'certification',
                'type_class' => 'c3',
                'type_label' => '자격증',
                'title' => $item->name,
                'image_url' => $this->resolveThumbnailUrl($item->thumbnail_path),
                'url' => route('education_certification.application_ec_view_type2', $item->id),
                'info_label_1' => '신청기간',
                'info_value_1' => $this->formatShortPeriod($item->application_start, $item->application_end),
                'info_label_2' => '시험일',
                'info_value_2' => $item->exam_date ? $item->exam_date->format('y.m.d') : '',
            ]);
        }

        $onlines = OnlineEducation::where('is_public', true)
            ->orderBy('period_start', 'desc')
            ->limit(self::SLIDE_LIMIT_PER_TYPE)
            ->get();
        foreach ($onlines as $item) {
            $slides->push((object) [
                'type' => 'online',
                'type_class' => 'c4',
                'type_label' => '온라인 교육',
                'title' => $item->name,
                'image_url' => $this->resolveThumbnailUrl($item->thumbnail_path),
                'url' => route('education_certification.application_ec_view_online', $item->id),
                'info_label_1' => '신청기간',
                'info_value_1' => $this->formatShortPeriod($item->application_start, $item->application_end),
                'info_label_2' => '교육기간',
                'info_value_2' => $this->formatShortPeriod($item->period_start, $item->period_end),
            ]);
        }

        return $slides;
    }

    /**
     * 메인 세미나·해외연수 슬라이드용: 등록된 세미나/해외연수 프로그램을 조회해 공통 형식으로 반환
     */
    public function getSeminarTrainingSlidesForMain(): Collection
    {
        $items = SeminarTraining::where('is_public', true)
            ->whereNot('application_status', '비공개')
            ->orderBy('period_start', 'desc')
            ->limit(6)
            ->get();

        return $items->map(fn ($item) => (object) [
            'type_class' => ($item->type ?? '') === '해외연수' ? 'c6' : 'c5',
            'type_label' => $item->type ?? '세미나',
            'title' => $item->name,
            'image_url' => $this->resolveThumbnailUrl($item->thumbnail_path),
            'url' => route('seminars_training.application_st_view', $item->id),
            'info_label_1' => '신청기간',
            'info_value_1' => $this->formatShortPeriod($item->application_start, $item->application_end),
            'info_label_2' => '교육기간',
            'info_value_2' => $this->formatShortPeriod($item->period_start, $item->period_end),
        ]);
    }

    /**
     * 메인 공지사항 최신글 최대 4건
     */
    public function getNoticePostsForMain(): Collection
    {
        $posts = $this->boardPostService->getLatestPosts('notices', 4);
        return $posts->map(fn ($p) => (object) [
            'id' => $p->id,
            'title' => $p->title ?? '',
            'created_at' => $p->created_at ?? null,
            'url' => route('notice.notice_view', $p->id),
        ]);
    }

    /**
     * 메인 자료실 최신글 최대 4건
     */
    public function getDataRoomPostsForMain(): Collection
    {
        $posts = $this->boardPostService->getLatestPosts('library', 4);
        return $posts->map(fn ($p) => (object) [
            'id' => $p->id,
            'title' => $p->title ?? '',
            'created_at' => $p->created_at ?? null,
            'url' => route('notice.data_room_view', $p->id),
        ]);
    }

    /**
     * 썸네일 URL 해석 (교육·자격증 신청 페이지와 동일)
     * DB에 /storage/... 형태로 저장된 값은 그대로 사용, 상대 경로일 때만 storage/ 접두사 적용
     */
    private function resolveThumbnailUrl(?string $thumbnailPath): string
    {
        if (empty($thumbnailPath)) {
            return self::DEFAULT_IMAGE;
        }
        $path = trim($thumbnailPath);
        if (str_starts_with($path, '/') || str_starts_with($path, 'http')) {
            return $path;
        }
        return '/storage/' . $path;
    }

    private function formatShortPeriod($start, $end): string
    {
        if (!$start && !$end) {
            return '';
        }
        $s = $start instanceof \Carbon\Carbon ? $start : ($start ? \Carbon\Carbon::parse($start) : null);
        $e = $end instanceof \Carbon\Carbon ? $end : ($end ? \Carbon\Carbon::parse($end) : null);
        if ($s && $e) {
            return $s->format('y.m.d') . '~' . $e->format('y.m.d');
        }
        return $s ? $s->format('y.m.d') : ($e ? $e->format('y.m.d') : '');
    }
}
