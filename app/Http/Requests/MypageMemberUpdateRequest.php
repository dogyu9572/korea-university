<?php

namespace App\Http\Requests;

use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MypageMemberUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('member')->check();
    }

    public function rules(): array
    {
        $memberId = (string) Auth::guard('member')->id();

        return [
            'name' => 'required|string|max:8',
            'phone_number' => 'required|string|unique:members,phone_number,' . $memberId,
            'email' => 'nullable|email|unique:members,email,' . $memberId,
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|max:10|confirmed',
            'password_confirmation' => 'nullable|string|same:password',
            'address_postcode' => 'nullable|string',
            'address_base' => 'nullable|string',
            'address_detail' => 'nullable|string',
            'school_name' => 'required|string',
            'is_school_representative' => 'nullable|boolean',
            'email_marketing_consent' => 'nullable|boolean',
            'kakao_marketing_consent' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '이름은 필수 입력 항목입니다.',
            'name.max' => '이름은 최대 8자까지 입력 가능합니다.',
            'phone_number.required' => '휴대폰번호는 필수 입력 항목입니다.',
            'phone_number.unique' => '이미 사용 중인 휴대폰번호입니다.',
            'email.email' => '올바른 이메일 형식이 아닙니다.',
            'email.unique' => '이미 사용 중인 이메일입니다.',
            'password.min' => '비밀번호는 최소 8자 이상이어야 합니다.',
            'password.max' => '비밀번호는 최대 10자까지 입력 가능합니다.',
            'password.confirmed' => '비밀번호와 비밀번호 확인이 일치하지 않습니다.',
            'password_confirmation.same' => '비밀번호와 비밀번호 확인이 일치하지 않습니다.',
            'school_name.required' => '학교명은 필수 입력 항목입니다.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $password = $this->input('password');
            if ($password) {
                $current = $this->input('current_password');
                $member = Auth::guard('member')->user();
                if (!$current || !Hash::check($current, $member->password)) {
                    $validator->errors()->add('current_password', '현재 비밀번호가 일치하지 않습니다.');
                }
            }

            $isRep = $this->boolean('is_school_representative');
            $schoolName = trim((string) $this->input('school_name', ''));
            if ($isRep && $schoolName !== '') {
                $memberId = Auth::guard('member')->id();
                $exists = Member::active()
                    ->where('school_name', $schoolName)
                    ->where('is_school_representative', true)
                    ->where('id', '!=', $memberId)
                    ->exists();
                if ($exists) {
                    $validator->errors()->add('is_school_representative', '해당 학교의 대표자가 이미 등록되어 있습니다.');
                }
            }
        });
    }

    /**
     * 서비스에 전달할 데이터 (current_password 제외)
     */
    public function getUpdateData(): array
    {
        $data = $this->validated();
        unset($data['current_password'], $data['password_confirmation']);
        $data['is_school_representative'] = (bool) ($data['is_school_representative'] ?? false);
        $data['email_marketing_consent'] = (bool) ($data['email_marketing_consent'] ?? false);
        $data['kakao_marketing_consent'] = (bool) ($data['kakao_marketing_consent'] ?? false);
        $data['kakao_marketing_consent_at'] = $data['kakao_marketing_consent'] ? now() : null;
        $data['email_marketing_consent_at'] = $data['email_marketing_consent'] ? now() : null;
        return $data;
    }
}
