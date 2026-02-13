<?php

namespace App\Http\Requests\EducationCertification;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class OnlineEducationApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user('member') !== null;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];
        if ($this->has('has_cash_receipt')) {
            $merge['has_cash_receipt'] = $this->input('has_cash_receipt') === '1' || $this->input('has_cash_receipt') === true ? '1' : '0';
        }
        if ($this->has('has_tax_invoice')) {
            $merge['has_tax_invoice'] = $this->input('has_tax_invoice') === '1' || $this->input('has_tax_invoice') === true ? '1' : '0';
        }
        if ($merge !== []) {
            $this->merge($merge);
        }
    }

    protected function failedValidation(Validator $validator): void
    {
        $redirect = redirect()
            ->route('education_certification.application_ec_e_learning', [
                'online_education_id' => (int) $this->input('online_education_id'),
            ])
            ->withErrors($validator)
            ->withInput();

        throw new ValidationException($validator, $redirect);
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
            'cash_receipt_purpose' => ['required_if:has_cash_receipt,1', 'nullable', 'string', 'in:소득공제용,사업자지출증빙용'],
            'cash_receipt_number' => ['required_if:has_cash_receipt,1', 'nullable', 'string', 'max:50'],
            'has_tax_invoice' => ['nullable', 'boolean'],
            'company_name' => ['required_if:has_tax_invoice,1', 'nullable', 'string', 'max:100'],
            'registration_number' => ['required_if:has_tax_invoice,1', 'nullable', 'string', 'max:50'],
            'contact_person_name' => ['required_if:has_tax_invoice,1', 'nullable', 'string', 'max:50'],
            'contact_person_email' => ['required_if:has_tax_invoice,1', 'nullable', 'email', 'max:100'],
            'contact_person_phone' => ['required_if:has_tax_invoice,1', 'nullable', 'string', 'max:20'],
            'business_registration' => ['required_if:has_tax_invoice,1', 'nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'online_education_id' => '온라인교육',
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

    /**
     * 검증 메시지 (한글, 영어 노출 방지)
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute을(를) 입력해주세요.',
            'required_if' => ':attribute을(를) 입력해주세요.',
            'email' => ':attribute 형식이 올바르지 않습니다.',
            'max.string' => ':attribute은(는) :max자 이하여야 합니다.',
            'integer' => '선택한 :attribute이(가) 올바르지 않습니다.',
            'exists' => '선택한 :attribute이(가) 올바르지 않습니다.',
            'in' => '선택한 :attribute이(가) 올바르지 않습니다.',
            'mimes' => ':attribute은(는) :values 형식만 가능합니다.',
            'max.file' => ':attribute은(는) 2MB 이하여야 합니다.',
            'file' => ':attribute은(는) 올바른 파일을 선택해주세요.',
        ];
    }
}
