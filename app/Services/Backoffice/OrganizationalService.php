<?php

namespace App\Services\Backoffice;

use App\Models\OrganizationalChart;
use App\Models\OrganizationalMember;
use Illuminate\Http\Request;

class OrganizationalService
{
    /**
     * 조직도 내용 조회
     */
    public function getChartContent()
    {
        return OrganizationalChart::getContent();
    }

    /**
     * 조직도 내용 저장/수정
     */
    public function updateChartContent($content)
    {
        return OrganizationalChart::updateContent($content);
    }

    /**
     * 구성원 목록 조회 (카테고리별, 정렬순서)
     */
    public function getMembers()
    {
        return OrganizationalMember::ordered()->get();
    }

    /**
     * 구성원 생성
     */
    public function createMember(array $data)
    {
        // 정렬 순서 설정: 입력값이 없으면 기존 최대값 + 1
        if (!isset($data['sort_order']) || $data['sort_order'] === null) {
            $maxSortOrder = OrganizationalMember::max('sort_order') ?? -1;
            $data['sort_order'] = $maxSortOrder + 1;
        }

        return OrganizationalMember::create($data);
    }

    /**
     * 구성원 수정
     */
    public function updateMember($id, array $data)
    {
        $member = OrganizationalMember::findOrFail($id);
        $member->update($data);
        return $member;
    }

    /**
     * 구성원 삭제
     */
    public function deleteMember($id)
    {
        $member = OrganizationalMember::findOrFail($id);
        $member->delete();
        return true;
    }

    /**
     * 정렬 순서 업데이트
     */
    public function updateMemberOrder(array $memberOrder)
    {
        foreach ($memberOrder as $item) {
            if (isset($item['id']) && isset($item['order'])) {
                $member = OrganizationalMember::find($item['id']);
                if ($member) {
                    $member->sort_order = $item['order'];
                    $member->save();
                }
            }
        }
        return true;
    }
}
