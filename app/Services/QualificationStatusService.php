<?php

namespace App\Services;

use App\Models\EducationApplication;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class QualificationStatusService
{
    /**
     * 회원의 자격증 신청 목록 (취소 제외)
     */
    public function getListForMember(int $memberId, Request $request): LengthAwarePaginator
    {
        $query = EducationApplication::query()
            ->where('member_id', $memberId)
            ->whereNotNull('certification_id')
            ->whereNull('cancelled_at')
            ->with(['certification']);

        if ($request->filled('name')) {
            $name = $request->name;
            $query->whereHas('certification', fn ($q) => $q->where('name', 'like', '%' . $name . '%'));
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();
    }

    /**
     * 회원 소유의 자격증 신청 단건 조회
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getDetailForQualificationMember(int $applicationId, int $memberId): EducationApplication
    {
        return EducationApplication::query()
            ->where('id', $applicationId)
            ->where('member_id', $memberId)
            ->whereNotNull('certification_id')
            ->whereNull('cancelled_at')
            ->with(['certification', 'member', 'examVenue'])
            ->firstOrFail();
    }
}
