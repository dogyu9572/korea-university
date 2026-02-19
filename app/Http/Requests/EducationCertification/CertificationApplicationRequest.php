<?php

namespace App\Http\Requests\EducationCertification;

use App\Models\Member;
use App\Support\TempUploadSessionHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CertificationApplicationRequest extends FormRequest
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
        if ($this->isMethod('POST') && $this->session()->has('certification_receipt_temp_files')) {
            TempUploadSessionHelper::restoreIntoRequest($this, 'certification_receipt_temp_files');
        }
    }

    protected function failedValidation(Validator $validator): void
    {
        TempUploadSessionHelper::saveToSession($this, ['id_photo', 'business_registration'], 'certification_receipt_temp_files');

        $redirect = redirect()
            ->route('education_certification.application_ec_receipt', [
                'certification_id' => (int) $this->input('certification_id'),
            ])
            ->withErrors($validator)
            ->withInput();

        throw new ValidationException($validator, $redirect);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $email = $this->input('contact_person_email');
            if (is_string($email) && $email !== '' && str_contains($email, '@')) {
                $domain = strtolower(explode('@', $email)[1] ?? '');
                $blocked = [
                    'example.com', 'example.org', 'example.net', 'example.edu',
                    'test.com', 'test.org', 'test.net',
                    'aaa.com', 'bbb.com', 'ccc.com',
                    'asdf.com', 'qwerty.com', 'sample.com', 'domain.com',
                    'localhost', 'homepage.com', 'temp.com', 'fake.com',
                ];
                if ($domain !== '' && in_array($domain, $blocked, true)) {
                    $validator->errors()->add('contact_person_email', '유효하지 않은 메일주소입니다.');
                }
            }
            $phone = $this->input('contact_person_phone');
            if (is_string($phone) && $phone !== '' && ! Member::isValidPhoneFormat($phone)) {
                $validator->errors()->add('contact_person_phone', '올바른 휴대폰번호 형식이 아닙니다. (예: 010-1234-5678)');
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'certification_id' => ['required', 'integer', 'exists:certifications,id'],
            'exam_venue_id' => ['required', 'integer', 'exists:categories,id'],
            'birth_date' => ['required', 'date'],
            'id_photo' => ['nullable', 'image', 'max:2048'],
            'applicant_name' => ['nullable', 'string', 'max:50'],
            'affiliation' => ['nullable', 'string', 'max:100'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'refund_account_holder' => ['nullable', 'string', 'max:50'],
            'refund_bank_name' => ['nullable', 'string', 'max:50'],
            'refund_account_number' => ['nullable', 'string', 'max:50'],
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
            'certification_id' => '자격증',
            'exam_venue_id' => '시험장',
            'birth_date' => '생년월일',
            'id_photo' => '증명사진',
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
     * 유효성 검사 메시지 (한글).
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute을(를) 입력해주세요.',
            'required_if' => ':attribute을(를) 입력해주세요.',
            'integer' => ':attribute을(를) 올바르게 선택해주세요.',
            'exists' => ':attribute을(를) 올바르게 선택해주세요.',
            'date' => ':attribute을(를) 올바른 형식으로 입력해주세요.',
            'image' => ':attribute은(는) 이미지 파일(jpg, jpeg, png)만 업로드 가능합니다.',
            'max' => ':attribute은(는) :max자 이하여야 합니다.',
            'max.numeric' => ':attribute은(는) :max 이하여야 합니다.',
            'max.file' => ':attribute은(는) 2MB 이하여야 합니다.',
            'max.string' => ':attribute은(는) :max자 이하여야 합니다.',
            'max.array' => ':attribute은(는) :max개 이하여야 합니다.',
            'email' => ':attribute을(를) 올바른 형식으로 입력해주세요.',
            'in' => ':attribute을(를) 올바르게 선택해주세요.',
            'mimes' => ':attribute은(는) :values 형식만 업로드 가능합니다.',
        ];
    }
}
