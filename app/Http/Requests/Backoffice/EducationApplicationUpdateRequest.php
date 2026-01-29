<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;

class EducationApplicationUpdateRequest extends FormRequest
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
            'applicant_name' => 'required|string|max:50',
            'affiliation' => 'nullable|string|max:100',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'application_date' => 'required|date',
            'is_completed' => 'nullable|boolean',
            'is_survey_completed' => 'nullable|boolean',
            'participation_fee' => 'nullable|numeric|min:0',
            'fee_type' => 'nullable|string|max:50',
            'payment_method' => 'nullable|array',
            'payment_method.*' => 'in:무통장입금,방문카드결제,온라인카드결제',
            'payment_status' => 'required|in:미입금,입금완료',
            'payment_date' => 'nullable|date',
            'tax_invoice_status' => 'nullable|in:미신청,신청완료,발행완료',
            'has_cash_receipt' => 'nullable|boolean',
            'cash_receipt_purpose' => 'nullable|string|max:50',
            'cash_receipt_number' => 'nullable|string|max:50',
            'has_tax_invoice' => 'nullable|boolean',
            'company_name' => 'nullable|string|max:100',
            'registration_number' => 'nullable|string|max:50',
            'contact_person_name' => 'nullable|string|max:50',
            'contact_person_email' => 'nullable|email|max:100',
            'contact_person_phone' => 'nullable|string|max:20',
            'refund_account_holder' => 'nullable|string|max:50',
            'refund_bank_name' => 'nullable|string|max:50',
            'refund_account_number' => 'nullable|string|max:50',
            'attachments.*' => 'nullable|file|max:10240',
            // 온라인교육 전용
            'course_status' => 'nullable|string|in:접수,승인,만료',
            'attendance_rate' => 'nullable|numeric|min:0|max:100',
            // 자격증 전용
            'score' => 'nullable|integer|min:0',
            'pass_status' => 'nullable|string|in:합격,불합격,미정',
            'exam_venue_id' => 'nullable|exists:categories,id',
            'exam_ticket_number' => 'nullable|string|max:50',
            'qualification_certificate_number' => 'nullable|string|max:50',
            'pass_confirmation_number' => 'nullable|string|max:50',
            'id_photo_path' => 'nullable|string|max:255',
            'id_photo' => 'nullable|file|image|mimes:jpg,jpeg,png|max:5120',
            'delete_id_photo' => 'nullable|boolean',
            'birth_date' => 'nullable|date',
            // 세미나/해외연수 전용
            'roommate_member_id' => 'nullable|exists:members,id',
            'roommate_name' => 'nullable|string|max:100',
            'roommate_phone' => 'nullable|string|max:20',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'applicant_name.required' => '신청자명을 입력해주세요.',
            'applicant_name.max' => '신청자명은 50자를 초과할 수 없습니다.',
            'phone_number.required' => '휴대폰 번호를 입력해주세요.',
            'phone_number.max' => '휴대폰 번호는 20자를 초과할 수 없습니다.',
            'email.email' => '올바른 이메일 형식이 아닙니다.',
            'email.max' => '이메일은 100자를 초과할 수 없습니다.',
            'application_date.required' => '신청일시를 입력해주세요.',
            'application_date.date' => '올바른 날짜 형식이 아닙니다.',
            'participation_fee.numeric' => '참가비는 숫자로 입력해주세요.',
            'participation_fee.min' => '참가비는 0 이상이어야 합니다.',
            'payment_status.required' => '결제상태를 선택해주세요.',
            'payment_status.in' => '올바른 결제상태를 선택해주세요.',
            'payment_date.date' => '올바른 날짜 형식이 아닙니다.',
            'tax_invoice_status.in' => '올바른 세금계산서 상태를 선택해주세요.',
            'attachments.*.file' => '올바른 파일을 업로드해주세요.',
            'attachments.*.max' => '첨부파일 크기는 10MB를 초과할 수 없습니다.',
            'course_status.in' => '올바른 수강상태를 선택해주세요.',
            'attendance_rate.numeric' => '수강률은 숫자로 입력해주세요.',
            'attendance_rate.min' => '수강률은 0 이상이어야 합니다.',
            'attendance_rate.max' => '수강률은 100 이하여야 합니다.',
            'pass_status.in' => '올바른 합격여부를 선택해주세요.',
            'exam_venue_id.exists' => '존재하지 않는 시험장입니다.',
            'birth_date.date' => '올바른 생년월일 형식이 아닙니다.',
            'roommate_member_id.exists' => '존재하지 않는 룸메이트 회원입니다.',
        ];
    }
}
