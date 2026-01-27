<?php

namespace App\Http\Requests\Backoffice;

use Illuminate\Foundation\Http\FormRequest;

class EducationContentRequest extends FormRequest
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
            'education_guide' => 'nullable|string',
            'certification_guide' => 'nullable|string',
            'expert_level_1' => 'nullable|string',
            'expert_level_2' => 'nullable|string',
            'seminar_guide' => 'nullable|string',
            'overseas_training_guide' => 'nullable|string',
        ];
    }
}
