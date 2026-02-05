<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeminarTraining extends Model
{
    protected $table = 'seminar_trainings';

    protected $fillable = [
        'type',
        'education_class',
        'total_sessions_class',
        'is_public',
        'application_status',
        'name',
        'period_start',
        'period_end',
        'period_time',
        'is_accommodation',
        'location',
        'target',
        'completion_criteria',
        'survey_url',
        'certificate_type',
        'completion_hours',
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
        'annual_fee',
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
        'is_accommodation' => 'boolean',
        'capacity_unlimited' => 'boolean',
        'capacity_per_school_unlimited' => 'boolean',
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
        'annual_fee' => 'decimal:2',
    ];

    /**
     * 첨부파일 관계
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(SeminarTrainingAttachment::class, 'seminar_training_id')->orderBy('order', 'asc');
    }

    /**
     * 교육 신청 관계
     */
    public function applications(): HasMany
    {
        return $this->hasMany(EducationApplication::class, 'seminar_training_id');
    }
}
