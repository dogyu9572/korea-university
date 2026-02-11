<?php

namespace App\Http\Requests\SeminarTraining;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SeminarTrainingApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('member') !== null;
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
            'cash_receipt_purpose' => ['nullable', 'string', 'in:소득공제용,사업자지출증빙용'],
            'cash_receipt_number' => ['nullable', 'string', 'max:50'],
            'has_tax_invoice' => ['nullable', 'boolean'],
            'company_name' => ['nullable', 'string', 'max:100'],
            'registration_number' => ['nullable', 'string', 'max:50'],
            'contact_person_name' => ['nullable', 'string', 'max:50'],
            'contact_person_email' => ['nullable', 'email', 'max:100'],
            'contact_person_phone' => ['nullable', 'string', 'max:20'],
            'business_registration' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'roommate_member_id' => ['nullable', 'integer', 'exists:members,id'],
            'roommate_name' => ['nullable', 'string', 'max:100'],
            'roommate_phone' => ['nullable', 'string', 'max:20'],
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
            'max.string' => ':attribute은(는) :max자 이하여야 합니다.',
            'exists' => '선택한 :attribute이(가) 올바르지 않습니다.',
            'in' => '선택한 :attribute이(가) 올바르지 않습니다.',
            'mimes' => ':attribute은(는) :values 형식만 가능합니다.',
        ];
    }
}
