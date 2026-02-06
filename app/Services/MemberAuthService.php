<?php

namespace App\Services;

use App\Models\Member;
use App\Services\Backoffice\MemberService;
use Laravel\Socialite\Contracts\User as ProviderUser;

class MemberAuthService
{
    public function __construct(private MemberService $memberService)
    {
    }

    /**
     * 네이버 소셜 로그인 회원 조회 또는 생성
     */
    public function findOrCreateMemberFromNaver(ProviderUser $providerUser): Member
    {
        return $this->findOrCreateMemberFromSocial('naver', $providerUser);
    }

    /**
     * 카카오 소셜 로그인 회원 조회 또는 생성
     */
    public function findOrCreateMemberFromKakao(ProviderUser $providerUser): Member
    {
        return $this->findOrCreateMemberFromSocial('kakao', $providerUser);
    }

    /**
     * 소셜 회원 조회 또는 생성 (join_type: naver | kakao)
     */
    private function findOrCreateMemberFromSocial(string $provider, ProviderUser $providerUser): Member
    {
        $loginId = $provider . '_' . $providerUser->getId();
        $email = $providerUser->getEmail();
        $name = $providerUser->getName() ?? $providerUser->getNickname() ?? '';

        $member = Member::active()
            ->where('join_type', $provider)
            ->where(function ($q) use ($loginId, $email) {
                $q->where('login_id', $loginId);
                if ($email !== null && $email !== '') {
                    $q->orWhere('email', $email);
                }
            })
            ->first();

        if ($member) {
            return $member;
        }

        $data = [
            'join_type' => $provider,
            'email' => $email ?: null,
            'login_id' => $loginId,
            'password' => null,
            'name' => $name ?: '회원',
            'phone_number' => null,
            'terms_agreed_at' => now(),
        ];

        return $this->memberService->createMember($data);
    }
}
