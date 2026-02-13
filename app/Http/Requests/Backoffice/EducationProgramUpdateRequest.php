<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;

class EducationProgramUpdateRequest extends FormRequest
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
            'education_type' => 'required|in:정기교육,수시교육',
            'education_class' => 'nullable|string|max:255',
            'is_public' => 'nullable|boolean',
            'application_status' => 'required|in:접수중,접수마감,접수예정,비공개',
            'name' => 'required|string|max:255',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'period_time' => 'nullable|string|max:255',
            'is_accommodation' => 'nullable|boolean',
            'location' => 'nullable|string|max:255',
            'target' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'completion_criteria' => 'nullable|string|max:500',
            'survey_url' => 'nullable|url|max:500',
            'certificate_type' => 'required|in:이수증,수료증',
            'completion_hours' => 'nullable|integer|min:0',
            'application_start_date' => 'nullable|date',
            'application_start_hour' => 'nullable|integer|min:0|max:23',
            'application_end_date' => 'nullable|date|after_or_equal:application_start_date',
            'application_end_hour' => 'nullable|integer|min:0|max:23',
            'capacity' => 'required_unless:capacity_unlimited,1|nullable|integer|min:0',
            'capacity_unlimited' => 'nullable|boolean',
            'payment_methods' => 'required|array|min:1',
            'payment_methods.*' => 'in:무통장입금,방문카드결제,온라인카드결제',
            'deposit_account' => 'nullable|string|max:500',
            'deposit_deadline_days' => 'nullable|integer|min:1|max:30',
            'fee_member_twin' => 'required|numeric|min:0',
            'fee_member_single' => 'required|numeric|min:0',
            'fee_member_no_stay' => 'required|numeric|min:0',
            'fee_guest_twin' => 'required|numeric|min:0',
            'fee_guest_single' => 'required|numeric|min:0',
            'fee_guest_no_stay' => 'required|numeric|min:0',
            'refund_twin_fee' => 'nullable|numeric|min:0',
            'refund_single_fee' => 'nullable|numeric|min:0',
            'refund_no_stay_fee' => 'nullable|numeric|min:0',
            'refund_twin_deadline_days' => 'nullable|integer|min:1|max:30',
            'refund_single_deadline_days' => 'nullable|integer|min:1|max:30',
            'refund_no_stay_deadline_days' => 'nullable|integer|min:1|max:30',
            'refund_same_day_fee' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments.*' => 'nullable|file|max:10240',
            'education_overview' => 'nullable|string',
            'education_schedule' => 'nullable|string',
            'fee_info' => 'nullable|string',
            'refund_policy' => 'nullable|string',
            'curriculum' => 'nullable|string',
            'education_notice' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'education_type.required' => '교육 유형을 선택해주세요.',
            'education_type.in' => '올바른 교육 유형을 선택해주세요.',
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
            'capacity.required_unless' => '정원을 입력해주세요.',
            'fee_member_twin.required' => '참가비(회원 2인1실)를 입력해주세요.',
            'fee_member_single.required' => '참가비(회원 1인1실)를 입력해주세요.',
            'fee_member_no_stay.required' => '참가비(회원 숙박안함)를 입력해주세요.',
            'fee_guest_twin.required' => '참가비(비회원 2인1실)를 입력해주세요.',
            'fee_guest_single.required' => '참가비(비회원 1인1실)를 입력해주세요.',
            'fee_guest_no_stay.required' => '참가비(비회원 숙박안함)를 입력해주세요.',
            'payment_methods.required' => '결제방법을 1개 이상 선택해주세요.',
            'payment_methods.min' => '결제방법을 1개 이상 선택해주세요.',
        ];
    }
}
