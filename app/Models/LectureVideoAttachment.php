<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LectureVideoAttachment extends Model
{
    protected $table = 'lecture_video_attachments';

    protected $fillable = [
        'lecture_video_id',
        'path',
        'name',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * 강의 영상 관계
     */
    public function lectureVideo(): BelongsTo
    {
        return $this->belongsTo(LectureVideo::class, 'lecture_video_id');
    }
}
