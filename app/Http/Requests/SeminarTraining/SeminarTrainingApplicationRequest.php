<?php

namespace App\Http\Requests\SeminarTraining;

use App\Models\Member;
use App\Support\TempUploadSessionHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\ValidationException;

class SeminarTrainingApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('member') !== null;
    }

    protected function prepareForValidation(): void
    {
        if ($this->isMethod('POST') && $this->session()->has('seminar_training_apply_temp_files')) {
            TempUploadSessionHelper::restoreIntoRequest($this, 'seminar_training_apply_temp_files');
        }
    }

    protected function failedValidation(Validator $validator): void
    {
        try {
            TempUploadSessionHelper::saveToSession($this, ['business_registration'], 'seminar_training_apply_temp_files');
        } catch (\Throwable $e) {
            report($e);
        }

        $redirectUrl = route('seminars_training.application_st_apply', [
            'seminar_training_id' => (int) $this->input('seminar_training_id'),
        ]);

        if ($this->ajax() || $this->wantsJson()) {
            $this->session()->flash('errors', (new ViewErrorBag)->put('default', $validator->errors()));
            $this->session()->flashInput($this->except(array_merge(['_token'], array_keys($this->allFiles()))));

            throw new HttpResponseException(
                response()->json(['success' => false, 'redirect' => $redirectUrl], 422)
            );
        }

        $redirect = redirect($redirectUrl)
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

    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(
            redirect()
                ->route('seminars_training.application_st_apply', ['seminar_training_id' => $this->input('seminar_training_id')])
                ->with('error', '로그인 후 신청할 수 있습니다.')
                ->withInput($this->except('_token'))
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'seminar_training_id' => ['required', 'integer', 'exists:seminar_trainings,id'],
            'fee_type' => ['required', 'string', 'max:50'],
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
            'roommate_member_id' => ['nullable', 'integer', 'exists:members,id'],
            'roommate_name' => ['nullable', 'string', 'max:100'],
            'roommate_phone' => ['nullable', 'string', 'max:20'],
            'request_notes' => ['nullable', 'string', 'max:2000'],
            'traveler_registration_agreed' => ['required', 'accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'seminar_training_id' => '세미나·해외연수 프로그램',
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
            'roommate_member_id' => '룸메이트 회원',
            'roommate_name' => '룸메이트 이름',
            'roommate_phone' => '룸메이트 휴대폰',
            'request_notes' => '요청사항',
            'traveler_registration_agreed' => '여행자 가입 동의',
        ];
    }

    /**
     * 검증 메시지 (한글)
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute을(를) 입력해주세요.',
            'required_if' => ':attribute을(를) 입력해주세요.',
            'email' => ':attribute 형식이 올바르지 않습니다.',
            'max' => ':attribute은(는) :max자 이하여야 합니다.',
            'max.string' => ':attribute은(는) :max자 이하여야 합니다.',
            'exists' => '선택한 :attribute이(가) 올바르지 않습니다.',
            'in' => '선택한 :attribute이(가) 올바르지 않습니다.',
            'mimes' => ':attribute은(는) :values 형식만 가능합니다.',
            'accepted' => ':attribute에 체크해주세요.',
        ];
    }
}
