<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationApplicationAttachment extends Model
{
    protected $table = 'education_application_attachments';

    protected $fillable = [
        'education_application_id',
        'path',
        'name',
        'type',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * 교육 신청 관계
     */
    public function educationApplication(): BelongsTo
    {
        return $this->belongsTo(EducationApplication::class, 'education_application_id');
    }
}
