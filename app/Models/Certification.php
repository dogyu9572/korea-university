<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Certification extends Model
{
    protected $table = 'certifications';

    protected $fillable = [
        'level',
        'name',
        'exam_date',
        'venue_category_ids',
        'exam_method',
        'passing_score',
        'eligibility',
        'thumbnail_path',
        'is_public',
        'application_start',
        'application_end',
        'capacity',
        'capacity_unlimited',
        'application_status',
        'payment_methods',
        'deposit_account',
        'deposit_deadline_days',
        'exam_fee',
        'exam_overview',
        'exam_trend',
        'exam_venue',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'application_start' => 'datetime',
        'application_end' => 'datetime',
        'is_public' => 'boolean',
        'capacity_unlimited' => 'boolean',
        'payment_methods' => 'array',
        'venue_category_ids' => 'array',
        'passing_score' => 'integer',
        'capacity' => 'integer',
        'deposit_deadline_days' => 'integer',
        'exam_fee' => 'decimal:2',
    ];

    /**
     * 첨부파일 관계
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(CertificationAttachment::class, 'certification_id')->orderBy('order', 'asc');
    }

    /**
     * 자격증 신청 관계
     */
    public function applications(): HasMany
    {
        return $this->hasMany(EducationApplication::class, 'certification_id');
    }
}
