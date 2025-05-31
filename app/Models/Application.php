<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'internship_id',
        'student_id',
        'resume_url',
        'ktp_url',
        'transkipNilai_url',
        'status',
        'surat_balasan_url',
        'surat_balasan_at'
    ];

    public function internship() : BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }

    public function student() : BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
