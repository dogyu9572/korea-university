<?php

namespace App\Http\Requests;

use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;

class MemberSnsJoinRequest extends FormRequest
{
    public function authorize(): bool
    {
        $snsData = session('sns_join_data');
        if (!$snsData || !isset($snsData['join_type'], $snsData['login_id'])) {
            return false;
        }
        $joinType = $this->input('join_type');
        $loginId = $this->input('login_id');
        if (!in_array($joinType, ['naver', 'kakao'])) {
            return false;
        }
        if ($loginId !== ($snsData['login_id'] ?? '')) {
            return false;
        }
        return true;
    }

    public function rules(): array
    {
        return [
            'join_type' => 'required|in:naver,kakao',
            'login_id' => 'required|string|max:255|unique:members,login_id',
            'email' => 'required|email|unique:members,email',
            'phone_number' => 'required|string',
            'name' => 'required|string|max:8',
            'address_postcode' => 'nullable|string',
            'address_base' => 'nullable|string',
            'address_detail' => 'nullable|string',
            'school_name' => 'required|string',
            'is_school_representative' => 'nullable|boolean',
            'email_marketing_consent' => 'nullable|boolean',
            'kakao_marketing_consent' => 'nullable|boolean',
            'terms_privacy' => 'required|accepted',
            'terms_service' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'join_type.required' => '가입 유형 정보가 없습니다. SNS 로그인을 다시 시도해 주세요.',
            'join_type.in' => '올바르지 않은 가입 유형입니다.',
            'login_id.required' => '로그인 ID 정보가 없습니다. SNS 로그인을 다시 시도해 주세요.',
            'login_id.unique' => '이미 등록된 SNS 계정입니다.',
            'email.required' => '이메일은 필수 입력 항목입니다.',
            'email.email' => '올바른 이메일 형식이 아닙니다.',
            'email.unique' => '이미 사용 중인 이메일입니다.',
            'phone_number.required' => '휴대폰번호는 필수 입력 항목입니다.',
            'phone_number.unique' => '이미 사용 중인 휴대폰번호입니다.',
            'name.required' => '이름은 필수 입력 항목입니다.',
            'name.max' => '이름은 최대 8자까지 입력 가능합니다.',
            'school_name.required' => '학교명은 필수 입력 항목입니다.',
            'terms_privacy.required' => '개인정보 수집 및 이용에 동의해 주세요.',
            'terms_privacy.accepted' => '개인정보 수집 및 이용에 동의해 주세요.',
            'terms_service.required' => '서비스 이용약관에 동의해 주세요.',
            'terms_service.accepted' => '서비스 이용약관에 동의해 주세요.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $email = $this->input('email');
            if (is_string($email) && str_contains($email, '@')) {
                $domain = strtolower(explode('@', $email)[1] ?? '');
                $blocked = [
                    'example.com', 'example.org', 'example.net', 'example.edu',
                    'test.com', 'test.org', 'test.net',
                    'aaa.com', 'bbb.com', 'ccc.com',
                    'asdf.com', 'qwerty.com', 'sample.com', 'domain.com',
                    'localhost', 'homepage.com', 'temp.com', 'fake.com',
                ];
                if ($domain !== '' && in_array($domain, $blocked, true)) {
                    $validator->errors()->add('email', '유효하지 않은 메일주소입니다.');
                }
            }

            $phone = $this->input('phone_number');
            if (is_string($phone) && $phone !== '' && !str_starts_with($phone, 'sns_')) {
                $normalized = Member::normalizePhone($phone);
                if ($normalized !== '' && Member::where('phone_number', $normalized)->exists()) {
                    $validator->errors()->add('phone_number', '이미 사용 중인 휴대폰번호입니다.');
                }
            }

            $isRep = $this->boolean('is_school_representative');
            $schoolName = trim((string) $this->input('school_name', ''));
            if (!$isRep || $schoolName === '') {
                return;
            }
            $exists = Member::active()
                ->where('school_name', $schoolName)
                ->where('is_school_representative', true)
                ->exists();
            if ($exists) {
                $validator->errors()->add('is_school_representative', '해당 학교의 대표자가 이미 등록되어 있습니다.');
            }
        });
    }

    public function getMemberData(): array
    {
        $data = $this->validated();
        unset($data['terms_privacy'], $data['terms_service']);
        $data['password'] = null;
        $data['terms_agreed_at'] = now();
        $data['is_school_representative'] = (bool) ($data['is_school_representative'] ?? false);
        $data['email_marketing_consent'] = (bool) ($data['email_marketing_consent'] ?? false);
        $data['kakao_marketing_consent'] = (bool) ($data['kakao_marketing_consent'] ?? false);
        if ($data['kakao_marketing_consent']) {
            $data['kakao_marketing_consent_at'] = now();
        }
        if ($data['email_marketing_consent']) {
            $data['email_marketing_consent_at'] = now();
        }
        return $data;
    }
}
