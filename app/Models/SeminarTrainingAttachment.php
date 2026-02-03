<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeminarTrainingAttachment extends Model
{
    protected $table = 'seminar_training_attachments';

    protected $fillable = [
        'seminar_training_id',
        'path',
        'name',
        'order',
    ];

    /**
     * 세미나/해외연수 관계
     */
    public function seminarTraining(): BelongsTo
    {
        return $this->belongsTo(SeminarTraining::class, 'seminar_training_id');
    }
}
