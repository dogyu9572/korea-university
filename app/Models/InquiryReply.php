<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InquiryReply extends Model
{
    protected $fillable = [
        'inquiry_id',
        'admin_id',
        'author',
        'content',
        'status',
        'reply_date',
    ];

    protected $casts = [
        'reply_date' => 'date',
    ];

    /**
     * 문의 관계
     */
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

    /**
     * 관리자 관계
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * 답변 첨부파일 관계
     */
    public function files()
    {
        return $this->hasMany(InquiryReplyFile::class);
    }
}
