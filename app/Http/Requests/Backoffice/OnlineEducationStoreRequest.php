<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;

class OnlineEducationStoreRequest extends FormRequest
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
            'education_class' => 'nullable|string|max:255',
            'is_public' => 'nullable|boolean',
            'application_status' => 'required|in:접수중,접수마감,접수예정,비공개',
            'name' => 'required|string|max:255',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'period_time' => 'nullable|string|max:255',
            'education_overview' => 'nullable|string',
            'education_schedule' => 'nullable|string',
            'fee_info' => 'nullable|string',
            'refund_policy' => 'nullable|string',
            'curriculum' => 'nullable|string',
            'education_notice' => 'nullable|string',
            'survey_url' => 'nullable|url|max:500',
            'certificate_type' => 'required|in:이수증,수료증',
            'completion_hours' => 'nullable|integer|min:0',
            'application_start_date' => 'nullable|date',
            'application_start_hour' => 'nullable|integer|min:0|max:23',
            'application_end_date' => 'nullable|date|after_or_equal:application_start_date',
            'application_end_hour' => 'nullable|integer|min:0|max:23',
            'capacity' => 'required_unless:capacity_unlimited,1|nullable|integer|min:0',
            'capacity_unlimited' => 'nullable|boolean',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'in:무통장입금,방문카드결제,온라인카드결제',
            'fee' => 'nullable|numeric|min:0',
            'is_free' => 'nullable|boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments.*' => 'nullable|file|max:10240',
            'lectures' => 'nullable|array',
            'lectures.*.lecture_name' => 'required_with:lectures|string|max:255',
            'lectures.*.instructor_name' => 'nullable|string|max:255',
            'lectures.*.lecture_time' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'application_status.required' => '접수상태를 선택해주세요.',
            'application_status.in' => '올바른 접수상태를 선택해주세요.',
            'name.required' => '교육명을 입력해주세요.',
            'name.max' => '교육명은 255자를 초과할 수 없습니다.',
            'period_end.after_or_equal' => '교육 종료일은 시작일 이후여야 합니다.',
            'certificate_type.required' => '이수증/수료증을 선택해주세요.',
            'certificate_type.in' => '올바른 증서 유형을 선택해주세요.',
            'application_end_date.after_or_equal' => '신청 종료일은 시작일 이후여야 합니다.',
            'thumbnail.image' => '썸네일은 이미지 파일이어야 합니다.',
            'thumbnail.max' => '썸네일 파일 크기는 2MB를 초과할 수 없습니다.',
            'attachments.*.file' => '올바른 파일을 업로드해주세요.',
            'attachments.*.max' => '첨부파일 크기는 10MB를 초과할 수 없습니다.',
            'lectures.*.lecture_name.required_with' => '강의명을 입력해주세요.',
            'fee.min' => '교육비는 0 이상이어야 합니다.',
            'capacity.required_unless' => '정원을 입력해주세요.',
        ];
    }
}
