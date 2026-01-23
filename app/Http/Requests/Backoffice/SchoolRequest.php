<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Backoffice\SchoolService;

class SchoolRequest extends FormRequest
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
        $schoolService = new SchoolService();
        $branches = $schoolService->getBranches();
        $currentYear = (int) date('Y');

        return [
            'branch' => ['required', 'in:' . implode(',', $branches)],
            'year' => ['required', 'integer', 'min:1970', 'max:' . $currentYear],
            'school_name' => ['required', 'string', 'max:255'],
            'is_member_school' => ['required', 'in:Y,N'],
            'url' => ['nullable', 'url', 'max:500'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'branch.required' => '지회를 선택해주세요.',
            'branch.in' => '올바른 지회를 선택해주세요.',
            'year.required' => '연도를 선택해주세요.',
            'year.integer' => '연도는 숫자여야 합니다.',
            'year.min' => '연도는 1970년 이상이어야 합니다.',
            'year.max' => '연도는 현재 연도 이하여야 합니다.',
            'school_name.required' => '학교명은 필수 입력 항목입니다.',
            'school_name.max' => '학교명은 255자를 초과할 수 없습니다.',
            'is_member_school.required' => '회원교 여부를 선택해주세요.',
            'is_member_school.in' => '회원교 여부는 Y 또는 N을 선택해주세요.',
            'url.url' => '올바른 URL 형식이 아닙니다.',
            'url.max' => 'URL은 500자를 초과할 수 없습니다.',
        ];
    }

}
