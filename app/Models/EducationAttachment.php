<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationAttachment extends Model
{
    protected $table = 'education_attachments';

    protected $fillable = [
        'education_program_id',
        'path',
        'name',
        'order',
    ];

    /**
     * 교육 프로그램 관계
     */
    public function educationProgram(): BelongsTo
    {
        return $this->belongsTo(EducationProgram::class, 'education_program_id');
    }
}
