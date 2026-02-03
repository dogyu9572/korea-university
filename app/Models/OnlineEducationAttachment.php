<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnlineEducationAttachment extends Model
{
    protected $table = 'online_education_attachments';

    protected $fillable = [
        'online_education_id',
        'path',
        'name',
        'order',
    ];

    /**
     * 온라인 교육 관계
     */
    public function onlineEducation(): BelongsTo
    {
        return $this->belongsTo(OnlineEducation::class, 'online_education_id');
    }
}
