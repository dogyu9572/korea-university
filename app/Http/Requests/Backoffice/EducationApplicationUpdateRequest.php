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
        ];
    }
}
