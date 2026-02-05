<?php

namespace App\Http\Requests;

use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;

class MemberJoinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:members,email',
            'password' => 'required|string|min:8|max:10|confirmed',
            'password_confirmation' => 'required|string|same:password',
            'phone_number' => 'required|string|unique:members,phone_number',
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
            'email.required' => '이메일은 필수 입력 항목입니다.',
            'email.email' => '올바른 이메일 형식이 아닙니다.',
            'email.unique' => '이미 사용 중인 이메일입니다.',
            'email_verified.in' => '이메일 중복확인을 해 주세요.',
            'password.required' => '비밀번호는 필수 입력 항목입니다.',
            'password.min' => '비밀번호는 최소 8자 이상이어야 합니다.',
            'password.max' => '비밀번호는 최대 10자까지 입력 가능합니다.',
            'password.confirmed' => '비밀번호와 비밀번호 확인이 일치하지 않습니다.',
            'password_confirmation.same' => '비밀번호와 비밀번호 확인이 일치하지 않습니다.',
            'phone_number.required' => '휴대폰번호는 필수 입력 항목입니다.',
            'phone_number.unique' => '이미 사용 중인 휴대폰번호입니다.',
            'phone_verified.in' => '휴대폰번호 중복확인을 해 주세요.',
            'name.required' => '이름은 필수 입력 항목입니다.',
            'name.max' => '이름은 최대 8자까지 입력 가능합니다.',
            'school_name.required' => '학교명은 필수 입력 항목입니다.',
            'terms_privacy.required' => '개인정보 수집 및 이용에 동의해 주세요.',
            'terms_privacy.accepted' => '개인정보 수집 및 이용에 동의해 주세요.',
            'terms_service.required' => '서비스 이용약관에 동의해 주세요.',
            'terms_service.accepted' => '서비스 이용약관에 동의해 주세요.',
        ];
    }

    /**
     * 학교당 대표자 1명 제한: 해당 학교에 이미 대표자가 있으면 에러
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
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

    /**
     * MemberService createMember에 넘길 데이터 (join_type=email 고정)
     */
    public function getMemberData(): array
    {
        $data = $this->validated();
        unset($data['password_confirmation'], $data['terms_privacy'], $data['terms_service'], $data['email_verified'], $data['phone_verified']);
        $data['join_type'] = 'email';
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
