<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationContent extends Model
{
    protected $table = 'education_contents';

    protected $fillable = [
        'education_guide',
        'certification_guide',
        'expert_level_1',
        'expert_level_2',
        'seminar_guide',
        'overseas_training_guide',
    ];

    /**
     * 교육 콘텐츠를 생성하거나 업데이트합니다.
     *
     * @param array $data
     * @return \App\Models\EducationContent
     */
    public static function updateOrCreateContents(array $data)
    {
        $content = self::first();

        if ($content) {
            $content->update($data);
        } else {
            $content = self::create($data);
        }

        return $content;
    }
}
