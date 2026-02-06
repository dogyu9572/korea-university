<?php

namespace App\Services\Backoffice;

use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;

class MemberService
{
    /**
     * 회원 목록 조회 (검색/필터링/페이지네이션)
     */
    public function getMembers(array $filters = [], int $perPage = 20)
    {
        $query = Member::active();

        // 가입 구분 필터
        if (isset($filters['join_type']) && $filters['join_type']) {
            $query->byJoinType($filters['join_type']);
        }

        // 가입일 범위 필터
        if (isset($filters['join_date_start']) || isset($filters['join_date_end'])) {
            $query->byJoinDateRange($filters['join_date_start'] ?? null, $filters['join_date_end'] ?? null);
        }

        // 수신동의 필터
        if (isset($filters['marketing_consent']) && is_array($filters['marketing_consent']) && count($filters['marketing_consent']) > 0) {
            $query->where(function($q) use ($filters) {
                if (in_array('email', $filters['marketing_consent'])) {
                    $q->orWhere('email_marketing_consent', true);
                }
                if (in_array('kakao', $filters['marketing_consent'])) {
                    $q->orWhere('kakao_marketing_consent', true);
                }
            });
        }

        // 검색어 필터
        if (isset($filters['search_type']) && isset($filters['search_term'])) {
            $query->search($filters['search_type'], $filters['search_term']);
        }

        // 정렬 (가입일시 내림차순)
        $query->orderBy('created_at', 'desc');

        // 페이지네이션
        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 회원 상세 조회
     */
    public function getMember(int $id)
    {
        return Member::findOrFail($id);
    }

    /**
     * 회원 등록
     */
    public function createMember(array $data)
    {
        // 비밀번호 해시화
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        }

        // 이메일 가입 시 login_id를 email과 동일하게 설정
        if (isset($data['join_type']) && $data['join_type'] === 'email' && isset($data['email'])) {
            $data['login_id'] = $data['email'];
        }

        // 약관 동의 일시 설정
        if (!isset($data['terms_agreed_at'])) {
            $data['terms_agreed_at'] = now();
        }

        // 휴대폰번호: 소셜 전용 값(sns_로 시작)은 그대로 저장, 그 외 숫자만 저장
        if (isset($data['phone_number'])) {
            if (!str_starts_with((string) $data['phone_number'], 'sns_')) {
                $data['phone_number'] = Member::normalizePhone($data['phone_number']);
            }
        }

        return Member::create($data);
    }

    /**
     * 회원 정보 수정
     */
    public function updateMember(int $id, array $data)
    {
        $member = Member::findOrFail($id);

        // 비밀번호 변경 시에만 해시화
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // password_confirmation은 저장하지 않음
        unset($data['password_confirmation']);

        // 휴대폰번호: 소셜 전용 값(sns_로 시작)은 그대로 저장, 그 외 숫자만 저장
        if (isset($data['phone_number'])) {
            if (!str_starts_with((string) $data['phone_number'], 'sns_')) {
                $data['phone_number'] = Member::normalizePhone($data['phone_number']);
            }
        }

        $member->update($data);
        return $member;
    }

    /**
     * 회원 삭제 (soft delete - withdrawn_at 설정)
     */
    public function deleteMember(int $id)
    {
        $member = Member::findOrFail($id);
        $member->update(['withdrawn_at' => now()]);
        return $member;
    }

    /**
     * 회원 일괄 삭제
     */
    public function deleteMembers(array $ids)
    {
        return Member::whereIn('id', $ids)
            ->whereNull('withdrawn_at')
            ->update(['withdrawn_at' => now()]);
    }

    /**
     * 회원 복원 (withdrawn_at null 처리)
     */
    public function restoreMember(int $id)
    {
        $member = Member::findOrFail($id);
        $member->update(['withdrawn_at' => null]);
        return $member;
    }

    /**
     * 엑셀 다운로드 (간단한 구현, 필요시 Laravel Excel 라이브러리 사용)
     */
    public function exportMembersToExcel(array $filters = [])
    {
        $query = Member::active();

        // 필터 적용 (getMembers와 동일한 로직)
        if (isset($filters['join_type']) && $filters['join_type']) {
            $query->byJoinType($filters['join_type']);
        }

        if (isset($filters['join_date_start']) || isset($filters['join_date_end'])) {
            $query->byJoinDateRange($filters['join_date_start'] ?? null, $filters['join_date_end'] ?? null);
        }

        if (isset($filters['marketing_consent']) && is_array($filters['marketing_consent']) && count($filters['marketing_consent']) > 0) {
            $query->where(function($q) use ($filters) {
                if (in_array('email', $filters['marketing_consent'])) {
                    $q->orWhere('email_marketing_consent', true);
                }
                if (in_array('kakao', $filters['marketing_consent'])) {
                    $q->orWhere('kakao_marketing_consent', true);
                }
            });
        }

        if (isset($filters['search_type']) && isset($filters['search_term'])) {
            $query->search($filters['search_type'], $filters['search_term']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->get();
    }

    /**
     * 이메일 중복 확인
     */
    public function checkDuplicateEmail(string $email, ?int $excludeId = null)
    {
        $query = Member::where('email', $email);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * 휴대폰 중복 확인
     */
    public function checkDuplicatePhone(string $phone, ?int $excludeId = null)
    {
        $phone = Member::normalizePhone($phone);
        $query = Member::where('phone_number', $phone);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * 탈퇴회원 목록 조회 (검색/필터링/페이지네이션)
     */
    public function getWithdrawnMembers(array $filters = [], int $perPage = 20)
    {
        $query = Member::withdrawn();

        // 가입 구분 필터
        if (isset($filters['join_type']) && $filters['join_type']) {
            $query->byJoinType($filters['join_type']);
        }

        // 탈퇴일 범위 필터
        if (isset($filters['withdrawal_date_start']) || isset($filters['withdrawal_date_end'])) {
            $query->byWithdrawalDateRange($filters['withdrawal_date_start'] ?? null, $filters['withdrawal_date_end'] ?? null);
        }

        // 검색어 필터
        if (isset($filters['search_type']) && isset($filters['search_term'])) {
            $query->search($filters['search_type'], $filters['search_term']);
        }

        // 정렬 (탈퇴일 내림차순)
        $query->orderBy('withdrawn_at', 'desc');

        // 페이지네이션
        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 회원 영구 삭제 (DB에서 완전 삭제)
     */
    public function forceDeleteMember(int $id)
    {
        $member = Member::withdrawn()->findOrFail($id);
        $member->delete();
        return $member;
    }

    /**
     * 회원 일괄 영구 삭제
     */
    public function forceDeleteMembers(array $ids)
    {
        return Member::withdrawn()
            ->whereIn('id', $ids)
            ->delete();
    }
}
