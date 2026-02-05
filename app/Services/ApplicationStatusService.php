<?php

namespace App\Services;

use App\Models\EducationApplication;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ApplicationStatusService
{
    /**
     * 회원 소유의 교육 신청 단건 조회 (자격증/취소 제외)
     *
     * @throws ModelNotFoundException
     */
    public function getDetailForMember(int $applicationId, int $memberId): EducationApplication
    {
        return EducationApplication::query()
            ->where('id', $applicationId)
            ->where('member_id', $memberId)
            ->whereNull('certification_id')
            ->whereNull('cancelled_at')
            ->with(['education', 'onlineEducation', 'seminarTraining', 'member', 'roommate'])
            ->firstOrFail();
    }

    /**
     * 회원 소유의 정기/수시/세미나/해외연수 신청만 조회 (view용)
     *
     * @throws ModelNotFoundException
     */
    public function getDetailForOfflineMember(int $applicationId, int $memberId): EducationApplication
    {
        return EducationApplication::query()
            ->where('id', $applicationId)
            ->where('member_id', $memberId)
            ->whereNull('certification_id')
            ->whereNull('cancelled_at')
            ->where(function ($q) {
                $q->whereNotNull('education_id')->orWhereNotNull('seminar_training_id');
            })
            ->with(['education', 'seminarTraining', 'member', 'roommate'])
            ->firstOrFail();
    }

    /**
     * 회원 소유의 온라인교육 신청만 조회 (view2용)
     *
     * @throws ModelNotFoundException
     */
    public function getDetailForOnlineMember(int $applicationId, int $memberId): EducationApplication
    {
        return EducationApplication::query()
            ->where('id', $applicationId)
            ->where('member_id', $memberId)
            ->whereNull('certification_id')
            ->whereNull('cancelled_at')
            ->whereNotNull('online_education_id')
            ->with(['onlineEducation.attachments', 'member'])
            ->firstOrFail();
    }

    /**
     * 회원의 교육 신청 현황 목록 (자격증 제외)
     */
    public function getListForMember(int $memberId, Request $request): LengthAwarePaginator
    {
        $query = EducationApplication::query()
            ->where('member_id', $memberId)
            ->whereNull('certification_id')
            ->whereNull('cancelled_at')
            ->with(['education', 'onlineEducation', 'seminarTraining']);

        if ($request->filled('name')) {
            $name = $request->name;
            $query->where(function ($q) use ($name) {
                $q->whereHas('education', fn ($q2) => $q2->where('name', 'like', '%' . $name . '%'))
                    ->orWhereHas('onlineEducation', fn ($q2) => $q2->where('name', 'like', '%' . $name . '%'))
                    ->orWhereHas('seminarTraining', fn ($q2) => $q2->where('name', 'like', '%' . $name . '%'));
            });
        }

        if ($request->filled('education_type') && $request->education_type !== '전체') {
            $type = $request->education_type;
            if ($type === '온라인교육') {
                $query->whereNotNull('online_education_id');
            } elseif (in_array($type, ['정기교육', '수시교육'])) {
                $query->whereHas('education', fn ($q) => $q->where('education_type', $type));
            } elseif (in_array($type, ['세미나', '해외연수'])) {
                $query->whereHas('seminarTraining', fn ($q) => $q->where('type', $type));
            }
        }

        if ($request->filled('period_year')) {
            $year = (int) $request->period_year;
            $month = $request->filled('period_month') ? (int) $request->period_month : null;
            $query->where(function ($q) use ($year, $month) {
                $q->whereHas('education', function ($q2) use ($year, $month) {
                    $q2->whereYear('period_start', $year);
                    if ($month !== null) {
                        $q2->whereMonth('period_start', $month);
                    }
                })
                    ->orWhereHas('onlineEducation', function ($q2) use ($year, $month) {
                        $q2->whereYear('period_start', $year);
                        if ($month !== null) {
                            $q2->whereMonth('period_start', $month);
                        }
                    })
                    ->orWhereHas('seminarTraining', function ($q2) use ($year, $month) {
                        $q2->whereYear('period_start', $year);
                        if ($month !== null) {
                            $q2->whereMonth('period_start', $month);
                        }
                    });
            });
        }

        if ($request->filled('status') && $request->status !== '전체') {
            $status = $request->status;
            $todayStart = now()->startOfDay();
            if ($status === '수료') {
                $query->where('is_completed', true);
            } elseif ($status === '미수료') {
                $query->where('is_completed', false)->where(function ($q) use ($todayStart) {
                    $q->whereHas('education', fn ($q2) => $q2->where('period_end', '<', $todayStart))
                        ->orWhereHas('onlineEducation', fn ($q2) => $q2->where('period_end', '<', $todayStart))
                        ->orWhereHas('seminarTraining', fn ($q2) => $q2->where('period_end', '<', $todayStart));
                });
            } elseif ($status === '신청완료') {
                $query->where('is_completed', false)->where(function ($q) use ($todayStart) {
                    $q->whereHas('education', fn ($q2) => $q2->whereNull('period_end')->orWhere('period_end', '>=', $todayStart))
                        ->orWhereHas('onlineEducation', fn ($q2) => $q2->whereNull('period_end')->orWhere('period_end', '>=', $todayStart))
                        ->orWhereHas('seminarTraining', fn ($q2) => $q2->whereNull('period_end')->orWhere('period_end', '>=', $todayStart));
                });
            }
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();
    }

    /**
     * 회원 본인 신청에 한해 수강 취소 처리
     */
    public function cancelApplication(int $applicationId, int $memberId): void
    {
        $application = EducationApplication::query()
            ->where('id', $applicationId)
            ->where('member_id', $memberId)
            ->whereNull('certification_id')
            ->whereNull('cancelled_at')
            ->firstOrFail();

        if ($application->is_completed) {
            throw new \InvalidArgumentException('이수 완료된 신청은 취소할 수 없습니다.');
        }

        $application->update(['cancelled_at' => now()]);
    }
}
