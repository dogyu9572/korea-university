<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InquiryReplyFile extends Model
{
    protected $fillable = [
        'inquiry_reply_id',
        'file_path',
        'file_name',
        'file_size',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    /**
     * 답변 관계
     */
    public function reply()
    {
        return $this->belongsTo(InquiryReply::class, 'inquiry_reply_id');
    }
}
