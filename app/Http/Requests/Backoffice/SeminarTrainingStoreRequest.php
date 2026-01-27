<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;

class SeminarTrainingStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'education_type' => 'required|in:세미나,해외연수',
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
            'annual_fee' => 'nullable|numeric|min:0',
            'application_start_date' => 'nullable|date',
            'application_start_hour' => 'nullable|integer|min:0|max:23',
            'application_end_date' => 'nullable|date|after_or_equal:application_start_date',
            'application_end_hour' => 'nullable|integer|min:0|max:23',
            'capacity' => 'nullable|integer|min:0',
            'capacity_unlimited' => 'nullable|boolean',
            'capacity_per_school' => 'nullable|integer|min:0',
            'capacity_per_school_unlimited' => 'nullable|boolean',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'in:무통장입금,방문카드결제,온라인카드결제',
            'deposit_account' => 'nullable|string|max:500',
            'deposit_deadline_days' => 'nullable|integer|min:1|max:7',
            'fee_member_twin' => 'nullable|numeric|min:0',
            'fee_member_single' => 'nullable|numeric|min:0',
            'fee_member_no_stay' => 'nullable|numeric|min:0',
            'fee_guest_twin' => 'nullable|numeric|min:0',
            'fee_guest_single' => 'nullable|numeric|min:0',
            'fee_guest_no_stay' => 'nullable|numeric|min:0',
            'refund_twin_fee' => 'nullable|numeric|min:0',
            'refund_single_fee' => 'nullable|numeric|min:0',
            'refund_no_stay_fee' => 'nullable|numeric|min:0',
            'refund_twin_deadline_days' => 'nullable|integer|min:1|max:30',
            'refund_single_deadline_days' => 'nullable|integer|min:1|max:30',
            'refund_no_stay_deadline_days' => 'nullable|integer|min:1|max:30',
            'refund_same_day_fee' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments.*' => 'nullable|file|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'education_type.required' => '구분을 선택해주세요.',
            'education_type.in' => '구분은 세미나 또는 해외연수만 선택할 수 있습니다.',
            'application_status.required' => '접수상태를 선택해주세요.',
            'application_status.in' => '올바른 접수상태를 선택해주세요.',
            'name.required' => '세미나/해외연수명을 입력해주세요.',
            'name.max' => '세미나/해외연수명은 255자를 초과할 수 없습니다.',
            'period_end.after_or_equal' => '교육 종료일은 시작일 이후여야 합니다.',
            'certificate_type.required' => '이수증/수료증을 선택해주세요.',
            'certificate_type.in' => '올바른 증서 유형을 선택해주세요.',
            'application_end_date.after_or_equal' => '신청 종료일은 시작일 이후여야 합니다.',
            'deposit_deadline_days.min' => '입금기한은 1일 이상이어야 합니다.',
            'deposit_deadline_days.max' => '입금기한은 7일 이하여야 합니다.',
            'thumbnail.image' => '썸네일은 이미지 파일이어야 합니다.',
            'thumbnail.max' => '썸네일 파일 크기는 2MB를 초과할 수 없습니다.',
            'attachments.*.file' => '올바른 파일을 업로드해주세요.',
            'attachments.*.max' => '첨부파일 크기는 10MB를 초과할 수 없습니다.',
        ];
    }
}
