<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberFindPwResetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'password' => 'required|string|min:8|max:10|confirmed',
            'password_confirmation' => 'required|string|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => '비밀번호를 입력해 주세요.',
            'password.min' => '비밀번호는 최소 8자 이상이어야 합니다.',
            'password.max' => '비밀번호는 최대 10자까지 입력 가능합니다.',
            'password.confirmed' => '비밀번호와 비밀번호 확인이 일치하지 않습니다.',
            'password_confirmation.required' => '비밀번호 확인을 입력해 주세요.',
            'password_confirmation.same' => '비밀번호와 비밀번호 확인이 일치하지 않습니다.',
        ];
    }

    /**
     * 비밀번호: 영문/숫자/특수문자 중 두 가지 이상 조합, 8~10자
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $password = $this->input('password');
            if (is_string($password) && strlen($password) >= 8 && strlen($password) <= 10) {
                $hasLetter = preg_match('/[a-zA-Z]/', $password);
                $hasDigit = preg_match('/[0-9]/', $password);
                $hasSpecial = preg_match('/[^a-zA-Z0-9]/', $password);
                $types = ($hasLetter ? 1 : 0) + ($hasDigit ? 1 : 0) + ($hasSpecial ? 1 : 0);
                if ($types < 2) {
                    $validator->errors()->add('password', '비밀번호는 영문/숫자/특수문자 중 두 가지 이상 조합, 8~10자로 입력해 주세요.');
                }
            }
        });
    }
}
