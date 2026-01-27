<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;

class LectureVideoStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'lecture_time' => 'required|integer|min:1',
            'instructor_name' => 'required|string|max:255',
            'video_url' => 'nullable|url|max:500',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments.*' => 'nullable|file|max:10240',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '강의 제목을 입력해주세요.',
            'title.max' => '강의 제목은 255자를 초과할 수 없습니다.',
            'lecture_time.required' => '강의시간을 입력해주세요.',
            'lecture_time.integer' => '강의시간은 숫자로 입력해주세요.',
            'lecture_time.min' => '강의시간은 1분 이상이어야 합니다.',
            'instructor_name.required' => '강사명을 입력해주세요.',
            'instructor_name.max' => '강사명은 255자를 초과할 수 없습니다.',
            'video_url.url' => '올바른 URL 형식을 입력해주세요.',
            'video_url.max' => '동영상 링크는 500자를 초과할 수 없습니다.',
            'thumbnail.image' => '썸네일은 이미지 파일이어야 합니다.',
            'thumbnail.max' => '썸네일 파일 크기는 2MB를 초과할 수 없습니다.',
            'attachments.*.file' => '올바른 파일을 업로드해주세요.',
            'attachments.*.max' => '첨부파일 크기는 10MB를 초과할 수 없습니다.',
        ];
    }
}
