<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MypageInquiryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('member')->check();
    }

    public function rules(): array
    {
        return [
            'category' => 'required|in:교육,자격증,세미나,해외연수,기타',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'files' => 'nullable|array|max:3',
            'files.*' => 'file|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'category.required' => '분류를 선택해 주세요.',
            'category.in' => '올바른 분류를 선택해 주세요.',
            'title.required' => '문의 제목을 입력해 주세요.',
            'title.max' => '문의 제목은 최대 255자까지 입력 가능합니다.',
            'content.required' => '문의 내용을 입력해 주세요.',
            'files.max' => '첨부파일은 최대 3개까지 선택할 수 있습니다.',
            'files.*.file' => '올바른 파일을 선택해 주세요.',
            'files.*.max' => '첨부파일은 1개당 10MB를 초과할 수 없습니다.',
        ];
    }
}
