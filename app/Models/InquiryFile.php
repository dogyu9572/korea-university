<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InquiryFile extends Model
{
    protected $fillable = [
        'inquiry_id',
        'file_path',
        'file_name',
        'file_size',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    /**
     * 문의 관계
     */
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }
}
