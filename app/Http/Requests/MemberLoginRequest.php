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
            'email' => ['required', 'string', 'not_regex:/[\s\x{AC00}-\x{D7A3}\x{1100}-\x{11FF}\x{3130}-\x{318F}]/u'],
            'password' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => '이메일(아이디)을 입력해 주세요.',
            'email.not_regex' => '이메일(아이디)에는 한글과 공백을 사용할 수 없습니다.',
            'password.required' => '비밀번호를 입력해 주세요.',
        ];
    }
}
