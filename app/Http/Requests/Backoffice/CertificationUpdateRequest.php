<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;

class CertificationUpdateRequest extends FormRequest
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
            'level' => 'required|in:1급 자격증,2급 자격증',
            'name' => 'required|string|max:255',
            'exam_date' => 'required|date',
            'venue_category_ids' => 'nullable|array',
            'venue_category_ids.*' => 'nullable|integer|exists:categories,id',
            'exam_method' => 'nullable|string|max:255',
            'passing_score' => 'nullable|integer|min:0',
            'eligibility' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'is_public' => 'nullable|boolean',
            'application_start_date' => 'nullable|date',
            'application_start_hour' => 'nullable|integer|min:0|max:23',
            'application_end_date' => 'nullable|date|after_or_equal:application_start_date',
            'application_end_hour' => 'nullable|integer|min:0|max:23',
            'capacity' => 'nullable|integer|min:0',
            'capacity_unlimited' => 'nullable|boolean',
            'application_status' => 'required|in:접수중,접수마감,접수예정,비공개',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'in:무통장입금,방문카드결제,온라인카드결제',
            'deposit_account' => 'nullable|string|max:500',
            'deposit_deadline_days' => 'nullable|integer|min:1|max:7',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_thumbnail' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'level.required' => '구분을 선택해주세요.',
            'level.in' => '올바른 구분을 선택해주세요.',
            'name.required' => '자격증명을 입력해주세요.',
            'name.max' => '자격증명은 255자를 초과할 수 없습니다.',
            'exam_date.required' => '시험일을 선택해주세요.',
            'exam_date.date' => '올바른 날짜 형식을 입력해주세요.',
            'application_status.required' => '접수상태를 선택해주세요.',
            'application_status.in' => '올바른 접수상태를 선택해주세요.',
            'application_end_date.after_or_equal' => '신청 종료일은 시작일 이후여야 합니다.',
            'thumbnail.image' => '썸네일은 이미지 파일이어야 합니다.',
            'thumbnail.max' => '썸네일 파일 크기는 2MB를 초과할 수 없습니다.',
            'deposit_deadline_days.min' => '입금기한은 1일 이상이어야 합니다.',
            'deposit_deadline_days.max' => '입금기한은 7일 이하여야 합니다.',
        ];
    }
}
