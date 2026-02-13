<?php

namespace App\Models;

use App\Models\LectureVideo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnlineEducationLecture extends Model
{
    protected $table = 'online_education_lectures';

    protected $fillable = [
        'online_education_id',
        'lecture_video_id',
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
     * 온라인 교육 관계
     */
    public function onlineEducation(): BelongsTo
    {
        return $this->belongsTo(OnlineEducation::class, 'online_education_id');
    }

    /**
     * 강의영상 관계 (video_url 재생용)
     */
    public function lectureVideo(): BelongsTo
    {
        return $this->belongsTo(LectureVideo::class, 'lecture_video_id');
    }
}
