<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationProgram extends Model
{
    protected $table = 'education_programs';

    protected $fillable = [
        'education_type',
        'education_class',
        'is_public',
        'application_status',
        'name',
        'period_start',
        'period_end',
        'period_time',
        'is_accommodation',
        'location',
        'target',
        'content',
        'completion_criteria',
        'survey_url',
        'certificate_type',
        'completion_hours',
        'annual_fee',
        'application_start',
        'application_end',
        'capacity',
        'capacity_unlimited',
        'capacity_per_school',
        'capacity_per_school_unlimited',
        'payment_methods',
        'deposit_account',
        'deposit_deadline_days',
        'fee_member_twin',
        'fee_member_single',
        'fee_member_no_stay',
        'fee_guest_twin',
        'fee_guest_single',
        'fee_guest_no_stay',
        'refund_twin_fee',
        'refund_single_fee',
        'refund_no_stay_fee',
        'refund_twin_deadline',
        'refund_single_deadline',
        'refund_no_stay_deadline',
        'refund_same_day_fee',
        'thumbnail_path',
        'fee',
        'is_free',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_accommodation' => 'boolean',
        'capacity_unlimited' => 'boolean',
        'capacity_per_school_unlimited' => 'boolean',
        'is_free' => 'boolean',
        'payment_methods' => 'array',
        'period_start' => 'date',
        'period_end' => 'date',
        'application_start' => 'datetime',
        'application_end' => 'datetime',
        'fee_member_twin' => 'decimal:2',
        'fee_member_single' => 'decimal:2',
        'fee_member_no_stay' => 'decimal:2',
        'fee_guest_twin' => 'decimal:2',
        'fee_guest_single' => 'decimal:2',
        'fee_guest_no_stay' => 'decimal:2',
        'refund_twin_fee' => 'decimal:2',
        'refund_single_fee' => 'decimal:2',
        'refund_no_stay_fee' => 'decimal:2',
        'refund_same_day_fee' => 'decimal:2',
        'fee' => 'decimal:2',
        'annual_fee' => 'decimal:2',
    ];

    /**
     * 교육 일정 관계
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(EducationSchedule::class, 'education_program_id')->orderBy('id', 'asc');
    }

    /**
     * 첨부파일 관계
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(EducationAttachment::class, 'education_program_id')->orderBy('order', 'asc');
    }

    /**
     * 강의영상 관계 (온라인 교육 전용)
     */
    public function lectures(): HasMany
    {
        return $this->hasMany(OnlineEducationLecture::class, 'education_program_id')->orderBy('order', 'asc');
    }
}
