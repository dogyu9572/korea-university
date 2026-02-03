<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificationAttachment extends Model
{
    protected $table = 'certification_attachments';

    protected $fillable = [
        'certification_id',
        'path',
        'name',
        'order',
    ];

    /**
     * 자격증 관계
     */
    public function certification(): BelongsTo
    {
        return $this->belongsTo(Certification::class, 'certification_id');
    }
}
