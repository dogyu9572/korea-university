<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationalMemberRequest extends FormRequest
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
            'category' => 'required|in:회장,부회장,사무국,지회,감사,고문',
            'name' => 'required|string|max:100',
            'position' => 'nullable|string|max:100',
            'affiliation' => 'nullable|string|max:200',
            'phone' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category.required' => '분류는 필수 입력 항목입니다.',
            'category.in' => '분류는 회장, 부회장, 사무국, 지회, 감사, 고문 중 하나여야 합니다.',
            'name.required' => '이름은 필수 입력 항목입니다.',
            'name.max' => '이름은 최대 100자까지 입력 가능합니다.',
            'position.max' => '직위는 최대 100자까지 입력 가능합니다.',
            'affiliation.max' => '소속기관은 최대 200자까지 입력 가능합니다.',
            'phone.max' => '휴대폰 번호는 최대 50자까지 입력 가능합니다.',
            'sort_order.integer' => '정렬 순서는 숫자여야 합니다.',
            'sort_order.min' => '정렬 순서는 0 이상이어야 합니다.',
        ];
    }
}
