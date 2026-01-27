<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnlineEducationLecture extends Model
{
    protected $table = 'online_education_lectures';

    protected $fillable = [
        'education_program_id',
        'lecture_name',
        'instructor_name',
        'lecture_time',
        'order',
    ];

    protected $casts = [
        'lecture_time' => 'integer',
        'order' => 'integer',
    ];

    /**
     * 교육 프로그램 관계
     */
    public function educationProgram(): BelongsTo
    {
        return $this->belongsTo(EducationProgram::class, 'education_program_id');
    }
}
