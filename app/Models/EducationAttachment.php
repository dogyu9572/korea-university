<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationAttachment extends Model
{
    protected $table = 'education_attachments';

    protected $fillable = [
        'education_id',
        'path',
        'name',
        'order',
    ];

    /**
     * 교육 관계
     */
    public function education(): BelongsTo
    {
        return $this->belongsTo(Education::class, 'education_id');
    }
}
