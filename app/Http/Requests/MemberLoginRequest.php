<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string',
            'password' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => '이메일(아이디)을 입력해 주세요.',
            'password.required' => '비밀번호를 입력해 주세요.',
        ];
    }
}
