<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;

class InquiryReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('content')) {
            $content = $this->input('content');
            
            // Summernote 빈 HTML 제거
            // <p><br></p>, <p></p>, <p>&nbsp;</p> 등의 빈 태그 제거
            $cleanedContent = preg_replace('/<p[^>]*>(\s|&nbsp;|<br\s*\/?>)*<\/p>/i', '', $content);
            $cleanedContent = preg_replace('/<div[^>]*>(\s|&nbsp;|<br\s*\/?>)*<\/div>/i', '', $cleanedContent);
            
            // HTML 태그 제거 후 공백만 남은 경우 확인
            $cleanContent = trim(strip_tags($cleanedContent));
            $cleanContent = preg_replace('/&nbsp;/i', ' ', $cleanContent);
            $cleanContent = trim($cleanContent);
            
            // 빈 내용이면 원본도 빈 문자열로 설정 (새 답변인 경우)
            // 기존 답변이 있는 경우는 Service에서 처리
            if (empty($cleanContent)) {
                $this->merge(['content' => '']);
            } else {
                // 실제 내용이 있으면 원본 HTML 유지
                $this->merge(['content' => $content]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'author' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:답변대기,답변완료'],
            'reply_date' => ['nullable', 'date'],
            'content' => ['required', 'string'],
            'attachments.*' => ['nullable', 'file', 'max:10240'], // 10MB
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // content 필드가 빈 HTML만 있는지 추가 검증
            // 단, 기존 답변이 있는 경우는 Service에서 처리하므로 여기서는 새 답변만 검증
            $content = $this->input('content', '');
            $cleanContent = trim(strip_tags($content));
            $cleanContent = preg_replace('/&nbsp;/i', ' ', $cleanContent);
            $cleanContent = trim($cleanContent);
            
            // 빈 내용이지만 기존 답변이 있을 수 있으므로, 
            // 실제로는 Service 레벨에서 기존 답변 내용을 유지하도록 처리
            // 여기서는 새 답변 생성 시에만 검증
            // (기존 답변이 있는지 여부는 컨트롤러/서비스에서 확인)
        });
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'author.required' => '작성자는 필수 입력 항목입니다.',
            'author.max' => '작성자는 255자를 초과할 수 없습니다.',
            'status.required' => '답변상태를 선택해주세요.',
            'status.in' => '답변상태는 답변대기 또는 답변완료를 선택해주세요.',
            'reply_date.date' => '등록일은 올바른 날짜 형식이어야 합니다.',
            'content.required' => '답변 내용은 필수 입력 항목입니다.',
            'files.*.file' => '올바른 파일을 업로드해주세요.',
            'files.*.max' => '파일 크기는 10MB를 초과할 수 없습니다.',
        ];
    }
}
