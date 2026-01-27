<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationSchedule extends Model
{
    protected $table = 'education_schedules';

    protected $fillable = [
        'education_program_id',
        'class_name',
        'schedule_start',
        'schedule_end',
        'location',
        'capacity',
        'note',
    ];

    protected $casts = [
        'schedule_start' => 'date',
        'schedule_end' => 'date',
    ];

    /**
     * 교육 프로그램 관계
     */
    public function educationProgram(): BelongsTo
    {
        return $this->belongsTo(EducationProgram::class, 'education_program_id');
    }
}
