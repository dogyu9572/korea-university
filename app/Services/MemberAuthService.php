<?php

namespace App\Services;

use App\Models\Member;
use App\Services\Backoffice\MemberService;
use Illuminate\Support\Arr;
use Laravel\Socialite\Contracts\User as ProviderUser;

class MemberAuthService
{
    public function __construct(private MemberService $memberService)
    {
    }

    /**
     * 소셜 회원 조회 (join_type: naver | kakao)
     * 기존 회원이 없으면 null 반환
     */
    public function findMemberFromSocial(string $provider, ProviderUser $providerUser): ?Member
    {
        $loginId = $provider . '_' . $providerUser->getId();
        $email = $providerUser->getEmail();

        return Member::active()
            ->where('join_type', $provider)
            ->where(function ($q) use ($loginId, $email) {
                $q->where('login_id', $loginId);
                if ($email !== null && $email !== '') {
                    $q->orWhere('email', $email);
                }
            })
            ->first();
    }

    /**
     * 회원가입 폼 기본값용 SNS 데이터 반환
     */
    public function getSnsJoinData(string $provider, ProviderUser $providerUser): array
    {
        $loginId = $provider . '_' . $providerUser->getId();
        $email = $providerUser->getEmail();
        $name = $this->resolveSocialName($provider, $providerUser);
        $phoneNumber = $this->resolveSocialPhoneNumber($provider, $providerUser);

        return [
            'join_type' => $provider,
            'login_id' => $loginId,
            'email' => $email ?: '',
            'name' => $name ?: '회원',
            'phone_number' => $phoneNumber,
        ];
    }

    /**
     * 소셜 제공처에서 이름 반환 (카카오 동의 항목 name → kakao_account.name)
     */
    private function resolveSocialName(string $provider, ProviderUser $providerUser): string
    {
        if ($provider === 'kakao' && method_exists($providerUser, 'getRaw')) {
            $raw = $providerUser->getRaw();
            $name = $raw['kakao_account']['name'] ?? null;
            if ($name !== null && $name !== '') {
                return $name;
            }
        }
        return $providerUser->getName() ?? $providerUser->getNickname() ?? '';
    }

    /**
     * 소셜 제공처에서 전화번호 반환 (DB 저장용: 숫자만, 표시는 formatPhoneForDisplay로 하이픈 자동 생성)
     * 카카오: kakao_account.phone_number | 네이버: response.mobile, response.mobile_e164 | 없으면 sns_식별자
     */
    private function resolveSocialPhoneNumber(string $provider, ProviderUser $providerUser): string
    {
        if (method_exists($providerUser, 'getRaw')) {
            $raw = $providerUser->getRaw();
            $phone = match ($provider) {
                'kakao' => Arr::get($raw, 'kakao_account.phone_number'),
                'naver' => Arr::get($raw, 'response.mobile') ?: $this->parseMobileE164(Arr::get($raw, 'response.mobile_e164')),
                default => null,
            };
            if ($phone !== null && $phone !== '') {
                return $this->normalizePhoneForDb($phone);
            }
        }
        return 'sns_' . $provider . '_' . $providerUser->getId();
    }

    /** SNS 전화번호를 DB 저장 형식(숫자만, 82→0 변환)으로 정규화 */
    private function normalizePhoneForDb(string $phone): string
    {
        $digits = Member::normalizePhone($phone);
        if ($digits === '') {
            return '';
        }
        if (str_starts_with($digits, '82') && strlen($digits) >= 12) {
            return '0' . substr($digits, 2);
        }
        return $digits;
    }

    /** mobile_e164(+821027889572)를 숫자만 추출하여 010xxxxxxxx 형식으로 변환 */
    private function parseMobileE164(?string $mobileE164): ?string
    {
        if ($mobileE164 === null || $mobileE164 === '') {
            return null;
        }
        $digits = preg_replace('/\D/', '', $mobileE164);
        if (str_starts_with($digits, '82') && strlen($digits) >= 12) {
            return '0' . substr($digits, 2);
        }
        return $digits !== '' ? $digits : null;
    }
}
