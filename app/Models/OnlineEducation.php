<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OnlineEducation extends Model
{
    protected $table = 'online_educations';

    protected $fillable = [
        'education_class',
        'is_public',
        'application_status',
        'name',
        'period_start',
        'period_end',
        'period_time',
        'target',
        'completion_criteria',
        'survey_url',
        'certificate_type',
        'completion_hours',
        'application_start',
        'application_end',
        'capacity',
        'capacity_unlimited',
        'payment_methods',
        'deposit_account',
        'deposit_deadline_days',
        'fee',
        'is_free',
        'thumbnail_path',
        'education_overview',
        'education_schedule',
        'fee_info',
        'refund_policy',
        'curriculum',
        'education_notice',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'capacity_unlimited' => 'boolean',
        'is_free' => 'boolean',
        'payment_methods' => 'array',
        'period_start' => 'date',
        'period_end' => 'date',
        'application_start' => 'datetime',
        'application_end' => 'datetime',
        'fee' => 'decimal:2',
    ];

    /**
     * 강의영상 관계
     */
    public function lectures(): HasMany
    {
        return $this->hasMany(OnlineEducationLecture::class, 'online_education_id')->orderBy('order', 'asc');
    }

    /**
     * 첨부파일 관계
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(OnlineEducationAttachment::class, 'online_education_id')->orderBy('order', 'asc');
    }

    /**
     * 교육 신청 관계
     */
    public function applications(): HasMany
    {
        return $this->hasMany(EducationApplication::class, 'online_education_id');
    }
}
