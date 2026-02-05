<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationApplication extends Model
{
    protected $table = 'education_applications';

    protected $fillable = [
        'application_number',
        'education_id',
        'online_education_id',
        'certification_id',
        'seminar_training_id',
        'member_id',
        'applicant_name',
        'affiliation',
        'phone_number',
        'email',
        'application_date',
        'is_completed',
        'certificate_number',
        'is_survey_completed',
        'receipt_number',
        'refund_account_holder',
        'refund_bank_name',
        'refund_account_number',
        'participation_fee',
        'fee_type',
        'payment_method',
        'payment_status',
        'payment_date',
        'tax_invoice_status',
        'has_cash_receipt',
        'cash_receipt_purpose',
        'cash_receipt_number',
        'has_tax_invoice',
        'company_name',
        'registration_number',
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',
        // 온라인교육 전용
        'course_status',
        'attendance_rate',
        // 자격증 전용
        'score',
        'pass_status',
        'exam_venue_id',
        'exam_ticket_number',
        'qualification_certificate_number',
        'pass_confirmation_number',
        'id_photo_path',
        'birth_date',
        // 세미나/해외연수 전용
        'roommate_member_id',
        'roommate_name',
        'roommate_phone',
        'cancelled_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'is_survey_completed' => 'boolean',
        'has_cash_receipt' => 'boolean',
        'has_tax_invoice' => 'boolean',
        'application_date' => 'datetime',
        'payment_date' => 'datetime',
        'participation_fee' => 'decimal:2',
        'payment_method' => 'array',
        'attendance_rate' => 'decimal:2',
        'score' => 'integer',
        'birth_date' => 'date',
        'cancelled_at' => 'datetime',
    ];

    /**
     * 교육 관계 (정기/수시)
     */
    public function education(): BelongsTo
    {
        return $this->belongsTo(Education::class, 'education_id');
    }

    /**
     * 온라인교육 관계
     */
    public function onlineEducation(): BelongsTo
    {
        return $this->belongsTo(OnlineEducation::class, 'online_education_id');
    }

    /**
     * 자격증 관계
     */
    public function certification(): BelongsTo
    {
        return $this->belongsTo(Certification::class, 'certification_id');
    }

    /**
     * 세미나/해외연수 관계
     */
    public function seminarTraining(): BelongsTo
    {
        return $this->belongsTo(SeminarTraining::class, 'seminar_training_id');
    }

    /**
     * 신청 대상 프로그램 (교육/온라인/자격증/세미나 중 하나)
     */
    public function getProgramAttribute()
    {
        return $this->education
            ?? $this->onlineEducation
            ?? $this->certification
            ?? $this->seminarTraining;
    }

    /**
     * 신청 대상 프로그램 ID (라우트용)
     */
    public function getProgramIdAttribute(): ?int
    {
        return $this->education_id ?? $this->online_education_id ?? $this->certification_id ?? $this->seminar_training_id;
    }

    /**
     * 신청자 회원 관계
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * 첨부파일 관계
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(EducationApplicationAttachment::class, 'education_application_id')->orderBy('order', 'asc');
    }

    /**
     * 룸메이트 회원 관계 (세미나/해외연수 전용)
     */
    public function roommate(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'roommate_member_id');
    }

    /**
     * 시험장 관계 (자격증 전용)
     */
    public function examVenue(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'exam_venue_id');
    }

    /**
     * 목록 표시용 진행상태 (신청완료/수료/미수료)
     * 수료=이수상태 Y, 미수료=교육기간 지남+이수 N, 그 외=신청완료(신청시)
     */
    public function getDisplayStatusAttribute(): string
    {
        if ($this->is_completed) {
            return '수료';
        }
        $program = $this->program;
        if ($program && $program->period_end) {
            $endOfPeriod = $program->period_end instanceof \Carbon\Carbon
                ? $program->period_end->endOfDay()
                : \Carbon\Carbon::parse($program->period_end)->endOfDay();
            if ($endOfPeriod->isPast()) {
                return '미수료';
            }
        }
        return '신청완료';
    }

    /**
     * 목록 표시용 교육구분 (정기교육/수시교육/온라인교육/세미나/해외연수)
     */
    public function getEducationTypeLabelAttribute(): string
    {
        if ($this->education_id && $this->relationLoaded('education') && $this->education) {
            return $this->education->education_type ?? '';
        }
        if ($this->online_education_id) {
            return '온라인교육';
        }
        if ($this->seminar_training_id && $this->relationLoaded('seminarTraining') && $this->seminarTraining) {
            return $this->seminarTraining->type ?? '';
        }
        return '';
    }

    /**
     * 자격증 신청 목록/상세용 표시상태 (접수완료/합격/불합격)
     * score 미입력 시 접수완료, score >= passing_score 시 합격, 그 외 불합격
     */
    public function getQualificationDisplayStatusAttribute(): string
    {
        if (!$this->certification_id) {
            return '접수완료';
        }
        $cert = $this->relationLoaded('certification') ? $this->certification : $this->certification()->first();
        if (!$cert) {
            return '접수완료';
        }
        $passing = (int) ($cert->passing_score ?? 0);
        if ($this->score === null) {
            return '접수완료';
        }
        return $this->score >= $passing ? '합격' : '불합격';
    }

    /**
     * 자격증 신청 합격 여부 (합격확인서/자격증 발급 조건)
     */
    public function getIsQualificationPassedAttribute(): bool
    {
        return $this->qualification_display_status === '합격';
    }
}
