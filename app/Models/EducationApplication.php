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
        'education_program_id',
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
    ];

    /**
     * 교육 프로그램 관계
     */
    public function educationProgram(): BelongsTo
    {
        return $this->belongsTo(EducationProgram::class, 'education_program_id');
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
}
