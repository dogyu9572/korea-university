<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MypageSecessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('member')->check();
    }

    public function rules(): array
    {
        return [
            'secession_agreed' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'secession_agreed.required' => '탈퇴 안내 내용에 동의해 주세요.',
            'secession_agreed.accepted' => '탈퇴 안내 내용에 동의해 주세요.',
        ];
    }
}
