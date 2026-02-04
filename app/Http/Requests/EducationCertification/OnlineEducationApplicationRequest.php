<?php

namespace App\Http\Requests\EducationCertification;

use Illuminate\Foundation\Http\FormRequest;

class OnlineEducationApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user('member') !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'online_education_id' => ['required', 'integer', 'exists:online_educations,id'],
            'fee_type' => ['nullable', 'string', 'max:50'],
            'applicant_name' => ['nullable', 'string', 'max:50'],
            'affiliation' => ['nullable', 'string', 'max:100'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'refund_account_holder' => ['required', 'string', 'max:50'],
            'refund_bank_name' => ['required', 'string', 'max:50'],
            'refund_account_number' => ['required', 'string', 'max:50'],
            'has_cash_receipt' => ['nullable', 'boolean'],
            'cash_receipt_purpose' => ['nullable', 'string', 'in:소득공제용,사업자지출증빙용'],
            'cash_receipt_number' => ['nullable', 'string', 'max:50'],
            'has_tax_invoice' => ['nullable', 'boolean'],
            'company_name' => ['required_if:has_tax_invoice,1', 'nullable', 'string', 'max:100'],
            'registration_number' => ['required_if:has_tax_invoice,1', 'nullable', 'string', 'max:50'],
            'contact_person_name' => ['required_if:has_tax_invoice,1', 'nullable', 'string', 'max:50'],
            'contact_person_email' => ['nullable', 'email', 'max:100'],
            'contact_person_phone' => ['nullable', 'string', 'max:20'],
            'business_registration' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'fee_type' => '참가비 유형',
            'refund_account_holder' => '환불 계좌 예금주명',
            'refund_bank_name' => '환불 계좌 은행명',
            'refund_account_number' => '환불 계좌번호',
            'cash_receipt_purpose' => '현금영수증 용도',
            'cash_receipt_number' => '현금영수증 발행번호',
            'company_name' => '상호명',
            'registration_number' => '사업자등록번호',
            'contact_person_name' => '담당자명',
            'contact_person_email' => '담당자 이메일',
            'contact_person_phone' => '담당자 연락처',
            'business_registration' => '사업자등록증',
        ];
    }
}
