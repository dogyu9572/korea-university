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
}
