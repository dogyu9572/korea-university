<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:200',
            'date' => 'required|date',
            'date_end' => 'nullable|date|after_or_equal:date',
            'content' => 'required|string|max:1000',
            'is_visible' => 'required|in:Y,N',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.max' => '제목은 최대 200자까지 입력 가능합니다.',
            'date.required' => '시작일은 필수 입력 항목입니다.',
            'date.date' => '올바른 날짜 형식이 아닙니다.',
            'date_end.date' => '종료일은 올바른 날짜 형식이어야 합니다.',
            'date_end.after_or_equal' => '종료일은 시작일 이후여야 합니다.',
            'content.required' => '내용은 필수 입력 항목입니다.',
            'content.max' => '내용은 최대 1000자까지 입력 가능합니다.',
            'is_visible.required' => '노출여부는 필수 선택 항목입니다.',
            'is_visible.in' => '노출여부는 Y 또는 N이어야 합니다.',
        ];
    }
}
