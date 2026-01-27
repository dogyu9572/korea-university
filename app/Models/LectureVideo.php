<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LectureVideo extends Model
{
    protected $table = 'lecture_videos';

    protected $fillable = [
        'title',
        'lecture_time',
        'instructor_name',
        'video_url',
        'thumbnail_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'lecture_time' => 'integer',
    ];

    /**
     * 첨부파일 관계
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(LectureVideoAttachment::class, 'lecture_video_id')->orderBy('order', 'asc');
    }
}
