<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'content',
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
    ];
}
