<?php

namespace App\Services;

use App\Models\Education;
use App\Models\Certification;
use App\Models\OnlineEducation;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EducationCertificationApplicationService
{
    private const PER_PAGE = 9;

    /**
     * 교육·자격증·온라인 프로그램 목록을 조회합니다.
     */
    public function getList(Request $request): LengthAwarePaginator
    {
        $tab = $request->get('tab', 'all');
        $tab = in_array($tab, ['all', 'education', 'certification', 'online']) ? $tab : 'all';
        $sort = $request->get('sort', 'created_at');
        $sort = in_array($sort, ['created_at', 'application_start']) ? $sort : 'created_at';

        if ($tab === 'all') {
            return $this->getMergedList($request, $sort);
        }

        if ($tab === 'education') {
            return $this->getEducationList($request, $sort);
        }

        if ($tab === 'certification') {
            return $this->getCertificationList($request, $sort);
        }

        return $this->getOnlineEducationList($request, $sort);
    }

    /**
     * 기간구분 연도 옵션을 반환합니다.
     */
    public function getPeriodYears(): array
    {
        $year = (int) now()->format('Y');
        return [$year + 1, $year, $year - 1];
    }

    /**
     * 탭별 총 개수를 반환합니다.
     */
    public function getTabCounts(): array
    {
        return [
            'all' => $this->getEducationQuery(null)->count()
                + $this->getCertificationQuery(null)->count()
                + $this->getOnlineEducationQuery(null)->count(),
            'education' => $this->getEducationQuery(null)->count(),
            'certification' => $this->getCertificationQuery(null)->count(),
            'online' => $this->getOnlineEducationQuery(null)->count(),
        ];
    }

    private function getEducationQuery(?Request $request)
    {
        $query = Education::query()
            ->where('is_public', true)
            ->whereNot('application_status', '비공개')
            ->withCount('applications as applications_count');

        if ($request && $request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request && $request->filled('education_type')) {
            $query->where('education_type', $request->education_type);
        }
        if ($request && $request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }
        $this->applyDateFilter($query, $request, 'education');

        return $query;
    }

    private function getCertificationQuery(?Request $request)
    {
        $query = Certification::query()
            ->where('is_public', true)
            ->whereNot('application_status', '비공개')
            ->withCount('applications as applications_count');

        if ($request && $request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request && $request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }
        $this->applyDateFilter($query, $request, 'certification');

        return $query;
    }

    private function getOnlineEducationQuery(?Request $request)
    {
        $query = OnlineEducation::query()
            ->where('is_public', true)
            ->whereNot('application_status', '비공개')
            ->withCount('applications as applications_count');

        if ($request && $request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request && $request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }
        $this->applyDateFilter($query, $request, 'online');

        return $query;
    }

    private function applyDateFilter($query, ?Request $request, string $modelType): void
    {
        if (!$request || !$request->filled('period_year')) {
            return;
        }
        $dateType = $request->get('date_type', 'application');
        $year = (int) $request->period_year;
        $month = $request->filled('period_month') ? (int) $request->period_month : null;

        $dateColumn = match (true) {
            $modelType === 'certification' && $dateType === 'exam' => 'exam_date',
            $modelType === 'certification' && $dateType === 'education' => 'exam_date',
            $modelType === 'certification' => 'application_start',
            $dateType === 'education' => 'period_start',
            default => 'application_start',
        };

        $query->whereYear($dateColumn, $year);
        if ($month) {
            $query->whereMonth($dateColumn, $month);
        }
    }

    private function getEducationList(Request $request, string $sort): LengthAwarePaginator
    {
        $query = $this->getEducationQuery($request);

        $query->orderBy($sort === 'application_start' ? 'application_start' : 'created_at', 'desc');

        $paginator = $query->paginate(self::PER_PAGE)->withQueryString();

        $paginator->getCollection()->each(function ($item) {
            $item->program_type = 'education';
        });

        return $paginator;
    }

    private function getCertificationList(Request $request, string $sort): LengthAwarePaginator
    {
        $query = $this->getCertificationQuery($request);

        $query->orderBy($sort === 'application_start' ? 'application_start' : 'created_at', 'desc');

        $paginator = $query->paginate(self::PER_PAGE)->withQueryString();

        $paginator->getCollection()->each(function ($item) {
            $item->program_type = 'certification';
        });

        return $paginator;
    }

    private function getOnlineEducationList(Request $request, string $sort): LengthAwarePaginator
    {
        $query = $this->getOnlineEducationQuery($request);

        $query->orderBy($sort === 'application_start' ? 'application_start' : 'created_at', 'desc');

        $paginator = $query->paginate(self::PER_PAGE)->withQueryString();

        $paginator->getCollection()->each(function ($item) {
            $item->program_type = 'online';
        });

        return $paginator;
    }

    private function getMergedList(Request $request, string $sort): LengthAwarePaginator
    {
        $educations = $this->getEducationQuery($request)->get();
        $certifications = $this->getCertificationQuery($request)->get();
        $onlineEducations = $this->getOnlineEducationQuery($request)->get();

        $educations->each(function ($item) {
            $item->program_type = 'education';
        });
        $certifications->each(function ($item) {
            $item->program_type = 'certification';
        });
        $onlineEducations->each(function ($item) {
            $item->program_type = 'online';
        });

        $merged = $educations->concat($certifications)->concat($onlineEducations);

        $sortKey = $sort === 'application_start' ? 'application_start' : 'created_at';
        $merged = $merged->sortByDesc(function ($item) use ($sortKey) {
            $val = $item->{$sortKey} ?? null;
            return $val ? $val->format('Y-m-d H:i:s') : '0000-00-00';
        })->values();

        $total = $merged->count();
        $page = (int) request('page', 1);
        $page = max(1, $page);
        $items = $merged->slice(($page - 1) * self::PER_PAGE, self::PER_PAGE)->values();

        return new LengthAwarePaginator(
            $items,
            $total,
            self::PER_PAGE,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
