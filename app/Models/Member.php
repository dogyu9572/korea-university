<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;

class Member extends Model implements AuthenticatableContract
{
    use Authenticatable;
    protected $fillable = [
        'join_type',
        'email',
        'login_id',
        'password',
        'name',
        'phone_number',
        'birth_date',
        'address_postcode',
        'address_base',
        'address_detail',
        'school_name',
        'is_school_representative',
        'email_marketing_consent',
        'kakao_marketing_consent',
        'sms_marketing_consent',
        'terms_agreed_at',
        'withdrawn_at',
    ];

    protected $casts = [
        'is_school_representative' => 'boolean',
        'email_marketing_consent' => 'boolean',
        'kakao_marketing_consent' => 'boolean',
        'sms_marketing_consent' => 'boolean',
        'terms_agreed_at' => 'datetime',
        'withdrawn_at' => 'datetime',
    ];

    /**
     * 탈퇴하지 않은 회원만 조회
     */
    public function scopeActive($query)
    {
        return $query->whereNull('withdrawn_at');
    }

    /**
     * 탈퇴한 회원만 조회
     */
    public function scopeWithdrawn($query)
    {
        return $query->whereNotNull('withdrawn_at');
    }

    /**
     * 가입 구분별 필터링
     */
    public function scopeByJoinType($query, $joinType)
    {
        if ($joinType && $joinType !== '전체') {
            return $query->where('join_type', $joinType);
        }
        return $query;
    }

    /**
     * 수신동의별 필터링
     */
    public function scopeByMarketingConsent($query, $type = null)
    {
        if ($type === 'email') {
            return $query->where('email_marketing_consent', true);
        } elseif ($type === 'kakao') {
            return $query->where('kakao_marketing_consent', true);
        } elseif ($type === 'sms') {
            return $query->where('sms_marketing_consent', true);
        }
        return $query;
    }

    /**
     * 이름/학교명/이메일/휴대폰/ID 검색
     */
    public function scopeSearch($query, $searchType, $searchTerm)
    {
        if (!$searchTerm || !$searchType || $searchType === '전체') {
            return $query;
        }

        switch ($searchType) {
            case '이름':
                return $query->where('name', 'like', '%' . $searchTerm . '%');
            case '학교명':
                return $query->where('school_name', 'like', '%' . $searchTerm . '%');
            case '이메일주소':
                return $query->where('email', 'like', '%' . $searchTerm . '%');
            case '휴대폰번호':
                return $query->where('phone_number', 'like', '%' . $searchTerm . '%');
            case 'ID':
                return $query->where('login_id', 'like', '%' . $searchTerm . '%');
            default:
                return $query;
        }
    }

    /**
     * 가입일 범위 필터링
     */
    public function scopeByJoinDateRange($query, $startDate = null, $endDate = null)
    {
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        return $query;
    }

    /**
     * 탈퇴일 범위 필터링
     */
    public function scopeByWithdrawalDateRange($query, $startDate = null, $endDate = null)
    {
        if ($startDate) {
            $query->whereDate('withdrawn_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('withdrawn_at', '<=', $endDate);
        }
        return $query;
    }

    /**
     * 휴대폰번호 숫자만 추출 (DB 저장·중복 확인용)
     */
    public static function normalizePhone(?string $phone): string
    {
        if ($phone === null || $phone === '') {
            return '';
        }
        return preg_replace('/\D/', '', $phone);
    }

    /**
     * 휴대폰번호 표시용 포맷 (010-1234-5678 등)
     */
    public static function formatPhoneForDisplay(?string $phone): string
    {
        if ($phone === null || $phone === '') {
            return '';
        }
        $digits = self::normalizePhone($phone);
        if (strlen($digits) === 11 && str_starts_with($digits, '010')) {
            return substr($digits, 0, 3) . '-' . substr($digits, 3, 4) . '-' . substr($digits, 7);
        }
        if (strlen($digits) === 10 && str_starts_with($digits, '02')) {
            return substr($digits, 0, 2) . '-' . substr($digits, 2, 3) . '-' . substr($digits, 5);
        }
        if (strlen($digits) === 10) {
            return substr($digits, 0, 3) . '-' . substr($digits, 3, 3) . '-' . substr($digits, 6);
        }
        return $phone;
    }

    /**
     * 아이디 찾기 결과용 마스킹 (앞 6자만 노출, 나머지는 *로 동일 길이 유지)
     */
    public static function maskLoginId(?string $loginId): string
    {
        if ($loginId === null || $loginId === '') {
            return '';
        }
        $len = mb_strlen($loginId);
        if ($len <= 6) {
            return str_repeat('*', $len);
        }
        return mb_substr($loginId, 0, 6) . str_repeat('*', $len - 6);
    }
}
