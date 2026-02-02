<?php

namespace App\Http\Requests;

use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;

class MemberFindPwRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $phone = $this->input('phone_number');
        if ($phone !== null && $phone !== '') {
            $this->merge(['phone_number' => Member::normalizePhone($phone)]);
        }
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'phone_number' => 'required|string|regex:/^[0-9]+$/',
            'name' => 'required|string|max:8',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => '이메일을 입력해 주세요.',
            'email.email' => '올바른 이메일 형식이 아닙니다.',
            'phone_number.required' => '휴대폰번호를 입력해 주세요.',
            'phone_number.regex' => '휴대폰번호는 숫자만 입력 가능합니다.',
            'name.required' => '이름을 입력해 주세요.',
            'name.max' => '이름은 8자 이내로 입력해 주세요.',
        ];
    }
}
