<?php

namespace App\Services;

use App\Models\Education;
use App\Models\Certification;
use App\Models\OnlineEducation;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
     * 교육 상세를 조회합니다. 비공개 시 null 반환.
     */
    public function getEducationDetail(int $id): ?Education
    {
        $item = Education::query()
            ->with(['attachments'])
            ->withCount('applications as applications_count')
            ->find($id);

        if (!$item || !$item->is_public || $item->application_status === '비공개') {
            return null;
        }

        return $item;
    }

    /**
     * 자격증 상세를 조회합니다. 비공개 시 null 반환.
     */
    public function getCertificationDetail(int $id): ?Certification
    {
        $item = Certification::query()
            ->with(['attachments'])
            ->withCount('applications as applications_count')
            ->find($id);

        if (!$item || !$item->is_public || $item->application_status === '비공개') {
            return null;
        }

        return $item;
    }

    /**
     * 온라인교육 상세를 조회합니다. 비공개 시 null 반환.
     */
    public function getOnlineEducationDetail(int $id): ?OnlineEducation
    {
        $item = OnlineEducation::query()
            ->with(['attachments', 'lectures'])
            ->withCount('applications as applications_count')
            ->find($id);

        if (!$item || !$item->is_public || $item->application_status === '비공개') {
            return null;
        }

        return $item;
    }

    /**
     * 교육 상세 뷰용 표시 데이터를 준비합니다.
     */
    public function prepareEducationDetailView(Education $e): array
    {
        $enrolled = $e->applications_count ?? 0;
        $capacity = $e->capacity ?? 0;
        $capacityUnlimited = $e->capacity_unlimited ?? false;
        $hasRemain = $capacityUnlimited || $enrolled < $capacity;

        $appPeriod = format_period_ko($e->application_start, $e->application_end);
        $periodText = $e->period_start && $e->period_end
            ? format_period_ko($e->period_start, $e->period_end)
            : format_period_ko($e->period_start, null, $e->period_time);

        $btn = get_application_button_state($e->application_status ?? '', 'education', $e->id);

        return [
            'type_class' => ($e->education_type ?? '') === '수시교육' ? 'c2' : 'c1',
            'education_type' => $e->education_type ?? '정기교육',
            'name' => $e->name,
            'app_period' => $appPeriod,
            'period_text' => $periodText,
            'education_class' => $e->education_class ?? '-',
            'accommodation_text' => ($e->is_accommodation ?? false) ? '합숙(숙박 선택 가능)' : '비합숙',
            'enrolled' => $enrolled,
            'capacity' => $capacity,
            'capacity_unlimited' => $capacityUnlimited,
            'capacity_text' => $capacityUnlimited ? '무제한' : $capacity . '명',
            'has_remain' => $hasRemain,
            'btn_class' => $btn['class'],
            'btn_text' => $btn['text'],
            'apply_url' => $btn['url'],
            'thumb' => $e->thumbnail_path ?: '/images/img_application_view_sample.jpg',
        ];
    }

    /**
     * 자격증 상세 뷰용 표시 데이터를 준비합니다.
     */
    public function prepareCertificationDetailView(Certification $c): array
    {
        $enrolled = $c->applications_count ?? 0;
        $capacity = $c->capacity ?? 0;
        $capacityUnlimited = $c->capacity_unlimited ?? false;
        $hasRemain = $capacityUnlimited || $enrolled < $capacity;

        $appPeriod = format_period_ko($c->application_start, $c->application_end);
        $examDate = format_date_ko($c->exam_date);

        $btn = get_application_button_state($c->application_status ?? '', 'certification', $c->id);

        return [
            'name' => $c->name,
            'app_period' => $appPeriod,
            'exam_date' => $examDate,
            'eligibility' => strip_tags($c->eligibility ?? '') ?: '-',
            'exam_method' => $c->exam_method ?? '-',
            'enrolled' => $enrolled,
            'capacity' => $capacity,
            'capacity_unlimited' => $capacityUnlimited,
            'capacity_text' => $capacityUnlimited ? '무제한' : $capacity . '명',
            'has_remain' => $hasRemain,
            'btn_class' => $btn['class'],
            'btn_text' => $btn['text'],
            'apply_url' => $btn['url'],
            'thumb' => $c->thumbnail_path ?: '/images/img_application_view_sample2.jpg',
        ];
    }

    /**
     * 온라인교육 상세 뷰용 표시 데이터를 준비합니다.
     */
    public function prepareOnlineEducationDetailView(OnlineEducation $o): array
    {
        $enrolled = $o->applications_count ?? 0;
        $capacity = $o->capacity ?? 0;
        $capacityUnlimited = $o->capacity_unlimited ?? false;
        $hasRemain = $capacityUnlimited || $enrolled < $capacity;

        $appPeriod = format_period_ko($o->application_start, $o->application_end);
        $periodText = format_period_ko($o->period_start, $o->period_end);

        $educationClass = $o->education_class ?? '';
        if (!empty($o->completion_hours)) {
            $educationClass .= ($educationClass ? '(' : '') . '인정시간: ' . $o->completion_hours . '시간' . ($educationClass ? ')' : '');
        }
        $educationClass = $educationClass ?: '-';

        $feeText = ($o->is_free ?? false) ? '무료' : ($o->fee ? number_format($o->fee) . '원' : '-');

        $btn = get_application_button_state($o->application_status ?? '', 'online', $o->id);

        return [
            'name' => $o->name,
            'app_period' => $appPeriod,
            'period_text' => $periodText,
            'enrolled' => $enrolled,
            'capacity' => $capacity,
            'capacity_unlimited' => $capacityUnlimited,
            'capacity_text' => $capacityUnlimited ? '무제한' : $capacity . '명',
            'has_remain' => $hasRemain,
            'target' => $o->target ?? '-',
            'education_class' => $educationClass,
            'fee_text' => $feeText,
            'btn_class' => $btn['class'],
            'btn_text' => $btn['text'],
            'apply_url' => $btn['url'],
            'thumb' => $o->thumbnail_path ?: '/images/img_application_view_sample.jpg',
        ];
    }

    /**
     * 교육 카드용 표시 데이터를 준비합니다.
     */
    public function prepareEducationCardData(Education $e): array
    {
        $enrolled = $e->applications_count ?? 0;
        $capacity = $e->capacity ?? 0;
        $capacityUnlimited = $e->capacity_unlimited ?? false;
        $periodText = $e->period_start && $e->period_end
            ? format_period_ko($e->period_start, $e->period_end)
            : format_period_ko($e->period_start, null, $e->period_time);

        return [
            'type_class' => ($e->education_type ?? '') === '수시교육' ? 'c2' : 'c1',
            'education_type' => $e->education_type ?? '정기교육',
            'name' => $e->name ?? '',
            'app_period' => format_period_ko($e->application_start, $e->application_end),
            'period_text' => $periodText,
            'enrolled' => $enrolled,
            'capacity' => $capacity,
            'capacity_unlimited' => $capacityUnlimited,
            'capacity_text' => $capacityUnlimited ? '무제한' : $capacity . '명',
            'has_remain' => $capacityUnlimited || $enrolled < $capacity,
            'target' => $e->target ?? '-',
            'education_class' => $e->education_class ?? '-',
            'location' => $e->location ?? '-',
            'fee_text' => format_education_fee($e),
            'btn' => get_application_button_state($e->application_status ?? '', 'education', $e->id),
            'view_url' => route('education_certification.application_ec_view', $e->id),
            'thumb' => $e->thumbnail_path ?: '/images/img_application_ec_sample.jpg',
        ];
    }

    /**
     * 자격증 카드용 표시 데이터를 준비합니다.
     */
    public function prepareCertificationCardData(Certification $c): array
    {
        $enrolled = $c->applications_count ?? 0;
        $capacity = $c->capacity ?? 0;
        $capacityUnlimited = $c->capacity_unlimited ?? false;

        return [
            'name' => $c->name ?? '',
            'eligibility' => Str::limit(strip_tags($c->eligibility ?? ''), 80) ?: '-',
            'app_period' => format_period_ko($c->application_start, $c->application_end),
            'exam_date' => format_date_ko($c->exam_date),
            'enrolled' => $enrolled,
            'capacity' => $capacity,
            'capacity_unlimited' => $capacityUnlimited,
            'capacity_text' => $capacityUnlimited ? '무제한' : $capacity . '명',
            'has_remain' => $capacityUnlimited || $enrolled < $capacity,
            'btn' => get_application_button_state($c->application_status ?? '', 'certification', $c->id),
            'view_url' => route('education_certification.application_ec_view_type2', $c->id),
            'thumb' => $c->thumbnail_path ?: '/images/img_application_ec_sample2.jpg',
        ];
    }

    /**
     * 온라인교육 카드용 표시 데이터를 준비합니다.
     */
    public function prepareOnlineEducationCardData(OnlineEducation $o): array
    {
        $enrolled = $o->applications_count ?? 0;
        $capacity = $o->capacity ?? 0;
        $capacityUnlimited = $o->capacity_unlimited ?? false;
        $educationClass = $o->education_class ?? '';
        if (!empty($o->completion_hours)) {
            $educationClass .= ($educationClass ? '(' : '') . '인정시간: ' . $o->completion_hours . '시간' . ($educationClass ? ')' : '');
        }
        $educationClass = $educationClass ?: '-';

        return [
            'name' => $o->name ?? '',
            'app_period' => format_period_ko($o->application_start, $o->application_end),
            'period_text' => format_period_ko($o->period_start, $o->period_end),
            'education_class' => $educationClass,
            'target' => $o->target ?? '-',
            'enrolled' => $enrolled,
            'capacity' => $capacity,
            'capacity_unlimited' => $capacityUnlimited,
            'capacity_text' => $capacityUnlimited ? '무제한' : $capacity . '명',
            'has_remain' => $capacityUnlimited || $enrolled < $capacity,
            'btn' => get_application_button_state($o->application_status ?? '', 'online', $o->id),
            'view_url' => route('education_certification.application_ec_view_online', $o->id),
            'thumb' => $o->thumbnail_path ?: '/images/img_application_ec_sample.jpg',
        ];
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
            $item->card_data = $this->prepareEducationCardData($item);
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
            $item->card_data = $this->prepareCertificationCardData($item);
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
            $item->card_data = $this->prepareOnlineEducationCardData($item);
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
            $item->card_data = $this->prepareEducationCardData($item);
        });
        $certifications->each(function ($item) {
            $item->program_type = 'certification';
            $item->card_data = $this->prepareCertificationCardData($item);
        });
        $onlineEducations->each(function ($item) {
            $item->program_type = 'online';
            $item->card_data = $this->prepareOnlineEducationCardData($item);
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
